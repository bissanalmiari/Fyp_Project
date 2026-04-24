import pickle
import warnings

import lightgbm as lgb
import numpy as np
import pandas as pd


TRAINING_DATA_PATH = "ltr_training_data.csv"
MODEL_PATH = "lightgbm_ranker.pkl"
FEATURES_PATH = "ranker_feature_columns.pkl"
IMPORTANCE_PATH = "feature_importance.csv"


EXCLUDED_COLUMNS = {
    "student_id",
    "query_id",
    "label",
    "label_source",
    "original_label",
    "original_label_source",
    "binary_label",
    "program_name",
    "university",
    "country",
    "study_level",
    "study_mode",
    "course_intensity",
    "broad_category",
    "detailed_category",
}


def load_training_data(csv_path: str = TRAINING_DATA_PATH) -> pd.DataFrame:
    df = pd.read_csv(csv_path)
    df = df.copy()
    df = df.replace([np.inf, -np.inf], np.nan)
    return df


def clean_training_data(df: pd.DataFrame) -> pd.DataFrame:
    df = df.copy()

    required_cols = {"query_id", "label"}
    missing = required_cols - set(df.columns)
    if missing:
        raise ValueError(f"Missing required columns in training data: {missing}")

    df = df.dropna(subset=["query_id", "label"]).copy()
    df["label"] = pd.to_numeric(df["label"], errors="coerce")
    df = df.dropna(subset=["label"]).copy()
    df["label"] = df["label"].clip(lower=0, upper=4).astype(int)

    query_sizes = df.groupby("query_id").size()
    valid_queries = query_sizes[query_sizes >= 2].index
    df = df[df["query_id"].isin(valid_queries)].copy()

    if df.empty:
        raise ValueError("Training data became empty after cleanup.")

    return df


def precision_hitrate_at_k(df_sorted, preds, k=3, relevant_threshold=3):
    temp = df_sorted.copy()
    temp["pred_score"] = preds

    precision_scores = []
    hit_scores = []

    for _, group_df in temp.groupby("query_id"):
        group_df = group_df.sort_values("pred_score", ascending=False)
        top_k = group_df.head(k)

        relevant = top_k["label"] >= relevant_threshold

        precision_k = relevant.sum() / k
        precision_scores.append(precision_k)

        hit_k = 1 if relevant.sum() > 0 else 0
        hit_scores.append(hit_k)

    return {
        f"precision@{k}": float(np.mean(precision_scores)) if precision_scores else 0.0,
        f"hit_rate@{k}": float(np.mean(hit_scores)) if hit_scores else 0.0,
    }


def get_feature_columns(df: pd.DataFrame) -> list:
    feature_cols = []

    for col in df.columns:
        if col in EXCLUDED_COLUMNS:
            continue

        if pd.api.types.is_numeric_dtype(df[col]):
            feature_cols.append(col)

    if not feature_cols:
        raise ValueError("No numeric feature columns found for training.")

    return sorted(feature_cols)


def split_by_student(
    df: pd.DataFrame,
    valid_ratio: float = 0.2,
    random_state: int = 42,
):
    student_ids = df["query_id"].drop_duplicates().tolist()

    rng = np.random.default_rng(random_state)
    rng.shuffle(student_ids)

    n_valid = max(1, int(len(student_ids) * valid_ratio))
    valid_students = set(student_ids[:n_valid])
    train_students = set(student_ids[n_valid:])

    train_df = df[df["query_id"].isin(train_students)].copy()
    valid_df = df[df["query_id"].isin(valid_students)].copy()

    return train_df, valid_df


def build_group_sizes(df: pd.DataFrame, query_col: str = "query_id") -> list:
    return df.groupby(query_col).size().tolist()


def build_row_weights(df: pd.DataFrame) -> np.ndarray:
    label_weights = {
        0: 1.0,
        1: 1.2,
        2: 4.0,
        3: 7.0,
        4: 11.0,
    }

    weights = df["label"].map(label_weights).fillna(1.0).astype(float).values
    return weights


def prepare_ranking_matrices(df: pd.DataFrame, feature_cols: list):
    df = df.sort_values(["query_id", "label"], ascending=[True, False]).reset_index(drop=True)

    X = df[feature_cols].fillna(0)
    y = df["label"].astype(int)
    group = build_group_sizes(df, query_col="query_id")
    weights = build_row_weights(df)

    return X, y, group, weights, df


def dcg_at_k(relevances, k):
    relevances = np.asarray(relevances)[:k]
    if len(relevances) == 0:
        return 0.0

    discounts = np.log2(np.arange(2, len(relevances) + 2))
    return np.sum((2 ** relevances - 1) / discounts)


def ndcg_at_k(y_true, y_score, k):
    if len(y_true) == 0:
        return 0.0

    order = np.argsort(-np.asarray(y_score))
    ranked_true = np.asarray(y_true)[order]

    ideal_order = np.argsort(-np.asarray(y_true))
    ideal_true = np.asarray(y_true)[ideal_order]

    dcg = dcg_at_k(ranked_true, k)
    idcg = dcg_at_k(ideal_true, k)

    if idcg == 0:
        return 0.0

    return dcg / idcg


def evaluate_grouped_ndcg(df_sorted: pd.DataFrame, preds: np.ndarray, ks=(1, 3, 5)):
    temp = df_sorted.copy()
    temp["pred_score"] = preds

    results = {}

    for k in ks:
        per_query_scores = []

        for _, group_df in temp.groupby("query_id"):
            y_true = group_df["label"].values
            y_score = group_df["pred_score"].values
            per_query_scores.append(ndcg_at_k(y_true, y_score, k))

        results[f"ndcg@{k}"] = float(np.mean(per_query_scores)) if per_query_scores else 0.0

    return results


def print_label_distribution(df: pd.DataFrame, title: str):
    print(f"\n{title}")
    print("-" * len(title))
    print(df["label"].value_counts(dropna=False).sort_index())


def train_lgbm_ranker(
    csv_path: str = TRAINING_DATA_PATH,
    model_path: str = MODEL_PATH,
    features_path: str = FEATURES_PATH,
    importance_path: str = IMPORTANCE_PATH,
):
    warnings.filterwarnings("ignore", category=UserWarning)

    df = load_training_data(csv_path)
    df = clean_training_data(df)

    feature_cols = get_feature_columns(df)

    train_df, valid_df = split_by_student(df, valid_ratio=0.2, random_state=42)

    if train_df.empty or valid_df.empty:
        raise ValueError("Train/validation split failed. Check query_id distribution.")

    X_train, y_train, group_train, w_train, train_sorted = prepare_ranking_matrices(train_df, feature_cols)
    X_valid, y_valid, group_valid, w_valid, valid_sorted = prepare_ranking_matrices(valid_df, feature_cols)

    print("=" * 70)
    print("TRAINING LIGHTGBM RANKER")
    print("=" * 70)
    print(f"Training rows: {len(train_sorted)}")
    print(f"Validation rows: {len(valid_sorted)}")
    print(f"Training queries (students): {train_sorted['query_id'].nunique()}")
    print(f"Validation queries (students): {valid_sorted['query_id'].nunique()}")
    print(f"Number of features: {len(feature_cols)}")

    print_label_distribution(train_sorted, "Training label distribution")
    print_label_distribution(valid_sorted, "Validation label distribution")

    train_set = lgb.Dataset(
        X_train,
        label=y_train,
        group=group_train,
        weight=w_train,
        feature_name=feature_cols,
        free_raw_data=False,
    )
    valid_set = lgb.Dataset(
        X_valid,
        label=y_valid,
        group=group_valid,
        weight=w_valid,
        reference=train_set,
        feature_name=feature_cols,
        free_raw_data=False,
    )

    params = {
        "objective": "lambdarank",
        "metric": "ndcg",
        "ndcg_eval_at": [1, 3, 5],
        "boosting_type": "gbdt",
        "learning_rate": 0.05,
        "num_leaves": 63,
        "max_depth": -1,
        "min_data_in_leaf": 20,
        "min_gain_to_split": 0.0,
        "bagging_fraction": 0.9,
        "bagging_freq": 1,
        "feature_fraction": 0.9,
        "lambda_l1": 0.3,
        "lambda_l2": 0.5,
        "label_gain": [0, 1, 3, 7, 15],
        "seed": 42,
        "feature_fraction_seed": 42,
        "bagging_seed": 42,
        "data_random_seed": 42,
        "num_threads": -1,
        "verbosity": -1,
    }

    model = lgb.train(
        params,
        train_set,
        num_boost_round=1000,
        valid_sets=[valid_set],
        valid_names=["valid"],
        callbacks=[lgb.log_evaluation(period=100)],
    )

    valid_preds = model.predict(X_valid)

    metrics = evaluate_grouped_ndcg(valid_sorted, valid_preds, ks=(1, 3, 5))
    extra_metrics = precision_hitrate_at_k(valid_sorted, valid_preds, k=3, relevant_threshold=3)

    print("\nAdditional metrics:")
    for k, v in extra_metrics.items():
        print(f"{k}: {v:.4f}")

    print("\nValidation ranking metrics:")
    for metric_name, metric_value in metrics.items():
        print(f"{metric_name}: {metric_value:.4f}")

    with open(model_path, "wb") as f:
        pickle.dump(model, f)

    with open(features_path, "wb") as f:
        pickle.dump(feature_cols, f)

    importance_df = pd.DataFrame({
        "feature": feature_cols,
        "importance": model.feature_importance(importance_type="gain"),
    }).sort_values("importance", ascending=False)

    importance_df.to_csv(importance_path, index=False)

    print(f"\nModel saved to: {model_path}")
    print(f"Feature list saved to: {features_path}")
    print(f"Feature importance saved to: {importance_path}")

    return model, feature_cols, metrics, importance_df


def load_ranker(
    model_path: str = MODEL_PATH,
    features_path: str = FEATURES_PATH,
):
    with open(model_path, "rb") as f:
        model = pickle.load(f)

    with open(features_path, "rb") as f:
        feature_cols = pickle.load(f)

    return model, feature_cols


def predict_ml_scores(input_df: pd.DataFrame, model=None, feature_cols=None):
    if model is None or feature_cols is None:
        model, feature_cols = load_ranker()

    temp_df = input_df.copy()

    for col in feature_cols:
        if col not in temp_df.columns:
            temp_df[col] = 0

    X = temp_df[feature_cols].fillna(0)
    preds = model.predict(X)

    result_df = temp_df.copy()
    result_df["ml_score"] = preds

    return result_df


if __name__ == "__main__":
    train_lgbm_ranker()
