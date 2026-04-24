import math
import hashlib
import numpy as np
import pandas as pd

from data_preprocessing import load_and_clean_data
from feature_engineering import extract_student_preferences
from scoring import score_program, get_best_tuition, is_budget_acceptable
from recommender import (
    build_favorite_profile,
    build_feedback_profile,
    build_program_lookup,
    build_ml_feature_row,
    get_cached_ranker,
    minmax_normalize,
    compute_ml_confidence,
    build_program_popularity_maps,
    normalize_candidate_rule_scores,
    build_known_positive_program_keys,
    is_known_positive_program,
    program_matches_detailed_interest,
    program_matches_broad_interest,
    program_matches_near_category,
    program_matches_dimensions,
)
from ml_ranker import predict_ml_scores


PROGRAMS_PATH = "Programs_encoded.csv"
STUDENTS_PATH = "students_updated.csv"
FAVORITES_PATH = "favorites.csv"
FEEDBACK_PATH = "feedback.csv"


def normalize_text(value):
    if value is None:
        return ""
    text = str(value).strip().lower()
    if text in {"nan", "none", "null", "na", "n/a", "<na>"}:
        return ""
    return text


def make_program_key(program_name, university_name):
    return (
        normalize_text(program_name),
        normalize_text(university_name),
    )


def build_ground_truth_map(favorites_df, feedback_df):
    ground_truth = {}

    if favorites_df is not None and not favorites_df.empty:
        for _, row in favorites_df.iterrows():
            student_id = int(row["student_id"])
            ground_truth.setdefault(student_id, set()).add(
                make_program_key(row.get("program_name", ""), row.get("uni_name", ""))
            )

    if feedback_df is not None and not feedback_df.empty:
        for _, row in feedback_df.iterrows():
            student_id = int(row["student_id"])
            rating = float(row.get("rating", 0))
            is_relevant = int(row.get("is_relevant", 0))

            if is_relevant == 1 or rating >= 4:
                ground_truth.setdefault(student_id, set()).add(
                    make_program_key(row.get("program_name", ""), row.get("uni_name", ""))
                )

    return ground_truth


def build_seen_interaction_map(favorites_df, feedback_df):
    seen = {}

    if favorites_df is not None and not favorites_df.empty:
        for _, row in favorites_df.iterrows():
            student_id = int(row["student_id"])
            seen.setdefault(student_id, set()).add(
                make_program_key(row.get("program_name", ""), row.get("uni_name", ""))
            )

    if feedback_df is not None and not feedback_df.empty:
        for _, row in feedback_df.iterrows():
            student_id = int(row["student_id"])
            seen.setdefault(student_id, set()).add(
                make_program_key(row.get("program_name", ""), row.get("uni_name", ""))
            )

    return seen


def choose_holdout_item(
    relevant_items,
    available_program_keys=None,
    strategy="catalog_random",
    student_id=None,
    random_seed=42,
):
    if not relevant_items:
        return None

    candidates = sorted(relevant_items)

    if available_program_keys is not None and strategy.startswith("catalog"):
        candidates = [item for item in candidates if item in available_program_keys]

    if not candidates:
        return None

    if strategy.endswith("random"):
        seed_text = f"{random_seed}:{student_id if student_id is not None else ''}"
        seed_value = int(hashlib.md5(seed_text.encode("utf-8")).hexdigest(), 16)
        return candidates[seed_value % len(candidates)]

    return candidates[0]


def row_matches_program_key(row, program_col, university_col, target_key):
    return make_program_key(row.get(program_col, ""), row.get(university_col, "")) == target_key


def remove_holdout_from_interactions(favorites_df, feedback_df, student_id, holdout_key):
    eval_favorites = favorites_df
    eval_feedback = feedback_df

    if favorites_df is not None and not favorites_df.empty:
        fav_mask = favorites_df.apply(
            lambda row: int(row["student_id"]) == student_id
            and row_matches_program_key(row, "program_name", "uni_name", holdout_key),
            axis=1,
        )
        eval_favorites = favorites_df.loc[~fav_mask].copy()

    if feedback_df is not None and not feedback_df.empty:
        feedback_mask = feedback_df.apply(
            lambda row: int(row["student_id"]) == student_id
            and row_matches_program_key(row, "program_name", "uni_name", holdout_key),
            axis=1,
        )
        eval_feedback = feedback_df.loc[~feedback_mask].copy()

    return eval_favorites, eval_feedback


def filter_ranked_seen_items(ranked, excluded_seen_items):
    if not excluded_seen_items:
        return ranked

    filtered = []
    for item in ranked:
        key = make_program_key(item["program_name"], item["university"])
        if key in excluded_seen_items:
            continue
        filtered.append(item)

    return filtered


def precision_at_k(recommended_keys, relevant_keys, k=3):
    top_k = recommended_keys[:k]
    if k == 0:
        return 0.0
    hits = sum(1 for item in top_k if item in relevant_keys)
    return hits / k


def hit_rate_at_k(recommended_keys, relevant_keys, k=3):
    top_k = recommended_keys[:k]
    return 1.0 if any(item in relevant_keys for item in top_k) else 0.0


def dcg_at_k(recommended_keys, relevant_keys, k=3):
    dcg = 0.0
    for i, item in enumerate(recommended_keys[:k], start=1):
        rel = 1 if item in relevant_keys else 0
        dcg += rel / math.log2(i + 1)
    return dcg


def idcg_at_k(num_relevant, k=3):
    ideal_hits = min(num_relevant, k)
    idcg = 0.0
    for i in range(1, ideal_hits + 1):
        idcg += 1 / math.log2(i + 1)
    return idcg


def ndcg_at_k(recommended_keys, relevant_keys, k=3):
    if not relevant_keys:
        return 0.0

    dcg = dcg_at_k(recommended_keys, relevant_keys, k=k)
    idcg = idcg_at_k(len(relevant_keys), k=k)

    if idcg == 0:
        return 0.0

    return dcg / idcg


def build_stage_configs(has_detailed):
    if has_detailed:
        return [
            {
                "stage": 1,
                "use_detailed": True,
                "strict_location": True,
                "allow_near_category": False,
                "require_mode": False,
                "require_intensity": False,
                "min_rule_score": 6.2,
            },
            {
                "stage": 2,
                "use_detailed": False,
                "strict_location": True,
                "allow_near_category": False,
                "require_mode": False,
                "require_intensity": False,
                "min_rule_score": 5.8,
            },
            {
                "stage": 3,
                "use_detailed": True,
                "strict_location": False,
                "allow_near_category": False,
                "require_mode": True,
                "require_intensity": True,
                "min_rule_score": 5.2,
            },
            {
                "stage": 4,
                "use_detailed": False,
                "strict_location": False,
                "allow_near_category": False,
                "require_mode": True,
                "require_intensity": True,
                "min_rule_score": 4.8,
            },
            {
                "stage": 5,
                "use_detailed": False,
                "strict_location": False,
                "allow_near_category": True,
                "require_mode": False,
                "require_intensity": False,
                "min_rule_score": 4.5,
            },
        ]
    else:
        return [
            {
                "stage": 1,
                "use_detailed": False,
                "strict_location": True,
                "allow_near_category": False,
                "require_mode": False,
                "require_intensity": False,
                "min_rule_score": 6.2,
            },
            {
                "stage": 2,
                "use_detailed": False,
                "strict_location": False,
                "allow_near_category": False,
                "require_mode": True,
                "require_intensity": True,
                "min_rule_score": 5.2,
            },
            {
                "stage": 3,
                "use_detailed": False,
                "strict_location": False,
                "allow_near_category": True,
                "require_mode": False,
                "require_intensity": False,
                "min_rule_score": 4.5,
            },
        ]


def program_matches_stage(program, prefs, stage_cfg):
    tuition = get_best_tuition(program)

    if not is_budget_acceptable(prefs, tuition, hard_limit=True, program_row=program):
        return False

    if stage_cfg["use_detailed"]:
        if not program_matches_detailed_interest(program, prefs):
            return False
    elif stage_cfg["allow_near_category"]:
        if not program_matches_near_category(program, prefs):
            return False
    else:
        if not program_matches_broad_interest(program, prefs):
            return False

    return program_matches_dimensions(
        program,
        prefs,
        require_location=stage_cfg["strict_location"],
        require_mode=stage_cfg["require_mode"],
        require_intensity=stage_cfg["require_intensity"],
    )


def build_stage_candidate_buckets(programs_records, prefs, stages):
    buckets = [[] for _ in stages]

    for program in programs_records:
        for idx, stage_cfg in enumerate(stages):
            if program_matches_stage(program, prefs, stage_cfg):
                buckets[idx].append(program)
                break

    return buckets


def collect_candidate_rows(
    student_row,
    programs_df,
    programs_records,
    favorites_df,
    feedback_df,
    program_lookup,
    program_favorite_counts,
    program_positive_feedback_counts,
):
    prefs = extract_student_preferences(student_row)
    favorite_profile = build_favorite_profile(
        prefs["student_id"], favorites_df, programs_df, program_lookup=program_lookup
    )
    feedback_profile = build_feedback_profile(
        prefs["student_id"], feedback_df, programs_df, program_lookup=program_lookup
    )

    all_candidates = []
    seen = set()

    has_detailed = bool(prefs.get("detailed_categories", []))
    stages = build_stage_configs(has_detailed)
    stage_buckets = build_stage_candidate_buckets(programs_records, prefs, stages)

    for stage_cfg, candidates in zip(stages, stage_buckets):
        for program in candidates:
            key = make_program_key(program.get("Program Name", ""), program.get("University Name", ""))
            if key in seen:
                continue

            rule_score, explanation, summary = score_program(
                prefs,
                program,
                favorite_profile=favorite_profile,
                feedback_profile=feedback_profile,
            )

            if rule_score <= 0:
                continue

            if rule_score < stage_cfg["min_rule_score"]:
                continue

            all_candidates.append({
                "student_id": prefs["student_id"],
                "program_name": program.get("Program Name"),
                "university": program.get("University Name"),
                "country": program.get("Country"),
                "rule_score": float(rule_score),
                "summary": summary,
                "explanation": explanation,
                "_program_row": program,
            })
            seen.add(key)

        if len(all_candidates) >= 70:
            break

    return prefs, favorite_profile, feedback_profile, all_candidates


def rank_rule_only(candidates):
    ranked = []
    for item in candidates:
        ranked.append({
            "student_id": item["student_id"],
            "program_name": item["program_name"],
            "university": item["university"],
            "country": item["country"],
            "score": item["rule_score"],
            "summary": item["summary"],
            "explanation": item["explanation"],
            "_program_row": item["_program_row"],
        })

    ranked.sort(key=lambda x: x["score"], reverse=True)
    return ranked


def rank_ml_only(
    candidates,
    prefs,
    favorite_profile,
    feedback_profile,
    program_favorite_counts,
    program_positive_feedback_counts,
):
    if not candidates:
        return []

    model, feature_cols = get_cached_ranker()
    if model is None or feature_cols is None:
        return []

    feature_rows = []
    for item in candidates:
        feature_rows.append(
            build_ml_feature_row(
                item["_program_row"],
                prefs,
                favorite_profile,
                feedback_profile,
                item["rule_score"],
                program_favorite_counts=program_favorite_counts,
                program_positive_feedback_counts=program_positive_feedback_counts,
            )
        )

    normalize_candidate_rule_scores(feature_rows)
    feature_df = pd.DataFrame(feature_rows)
    pred_df = predict_ml_scores(feature_df, model=model, feature_cols=feature_cols)
    ml_scores = pred_df["ml_score"].tolist()

    ranked = []
    for idx, item in enumerate(candidates):
        ranked.append({
            "student_id": item["student_id"],
            "program_name": item["program_name"],
            "university": item["university"],
            "country": item["country"],
            "score": float(ml_scores[idx]),
            "summary": item["summary"],
            "explanation": item["explanation"],
            "_program_row": item["_program_row"],
        })

    ranked.sort(key=lambda x: x["score"], reverse=True)
    return ranked


def get_dynamic_hybrid_weights(favorite_profile, feedback_profile, ml_confidence=0.0):
    favorite_count = len(favorite_profile.get("favorite_records", [])) if favorite_profile else 0
    feedback_count = len(feedback_profile.get("feedback_records", [])) if feedback_profile else 0
    interaction_count = favorite_count + feedback_count

    if interaction_count == 0:
        ml_weight = 1.0
    elif interaction_count < 3:
        ml_weight = 0.90
    elif interaction_count < 10:
        ml_weight = 0.80
    else:
        ml_weight = 0.70

    if ml_confidence >= 0.85:
        ml_weight = min(1.0, ml_weight + 0.05)
    elif ml_confidence <= 0.35:
        ml_weight = max(0.60, ml_weight - 0.05)

    rule_weight = 1.0 - ml_weight
    return ml_weight, rule_weight, interaction_count


def rank_hybrid(
    candidates,
    prefs,
    favorite_profile,
    feedback_profile,
    program_favorite_counts,
    program_positive_feedback_counts,
):
    if not candidates:
        return []

    model, feature_cols = get_cached_ranker()

    if model is None or feature_cols is None:
        return rank_rule_only(candidates)

    feature_rows = []
    for item in candidates:
        feature_rows.append(
            build_ml_feature_row(
                item["_program_row"],
                prefs,
                favorite_profile,
                feedback_profile,
                item["rule_score"],
                program_favorite_counts=program_favorite_counts,
                program_positive_feedback_counts=program_positive_feedback_counts,
            )
        )

    normalize_candidate_rule_scores(feature_rows)
    feature_df = pd.DataFrame(feature_rows)
    pred_df = predict_ml_scores(feature_df, model=model, feature_cols=feature_cols)

    ml_scores = pred_df["ml_score"].tolist()
    rule_scores = [item["rule_score"] for item in candidates]

    ml_scores_norm = minmax_normalize(ml_scores)
    rule_scores_norm = minmax_normalize(rule_scores)
    known_positive_keys = build_known_positive_program_keys(favorite_profile, feedback_profile)

    ranked = []
    for idx, item in enumerate(candidates):
        temp_item = {
            "rule_score_norm": rule_scores_norm[idx],
            "feature_strong_pref_match": int(feature_rows[idx].get("feature_strong_pref_match", 0)),
            "feature_academic_pref_match": int(feature_rows[idx].get("feature_academic_pref_match", 0)),
            "feature_within_budget": int(feature_rows[idx].get("feature_within_budget", 0)),
        }

        ml_confidence = compute_ml_confidence(temp_item, ml_scores_norm[idx])
        ml_weight, rule_weight, _ = get_dynamic_hybrid_weights(
            favorite_profile,
            feedback_profile,
            ml_confidence=ml_confidence,
        )

        final_score = (ml_weight * ml_scores_norm[idx]) + (rule_weight * rule_scores_norm[idx])
        if is_known_positive_program(item["program_name"], item["university"], known_positive_keys):
            final_score += 0.30

        ranked.append({
            "student_id": item["student_id"],
            "program_name": item["program_name"],
            "university": item["university"],
            "country": item["country"],
            "score": float(final_score),
            "summary": item["summary"],
            "explanation": item["explanation"],
            "_program_row": item["_program_row"],
        })

    ranked.sort(key=lambda x: x["score"], reverse=True)
    return ranked


def rank_ml_and_hybrid(
    candidates,
    prefs,
    favorite_profile,
    feedback_profile,
    program_favorite_counts,
    program_positive_feedback_counts,
    hybrid_ml_weight=0.90,
):
    if not candidates:
        return [], []

    model, feature_cols = get_cached_ranker()

    if model is None or feature_cols is None:
        return [], rank_rule_only(candidates)

    feature_rows = []
    for item in candidates:
        feature_rows.append(
            build_ml_feature_row(
                item["_program_row"],
                prefs,
                favorite_profile,
                feedback_profile,
                item["rule_score"],
                program_favorite_counts=program_favorite_counts,
                program_positive_feedback_counts=program_positive_feedback_counts,
            )
        )

    normalize_candidate_rule_scores(feature_rows)
    feature_df = pd.DataFrame(feature_rows)
    pred_df = predict_ml_scores(feature_df, model=model, feature_cols=feature_cols)

    ml_scores = pred_df["ml_score"].tolist()
    rule_scores = [item["rule_score"] for item in candidates]

    ml_scores_norm = minmax_normalize(ml_scores)
    rule_scores_norm = minmax_normalize(rule_scores)
    known_positive_keys = build_known_positive_program_keys(favorite_profile, feedback_profile)

    ml_ranked = []
    hybrid_ranked = []

    for idx, item in enumerate(candidates):
        common = {
            "student_id": item["student_id"],
            "program_name": item["program_name"],
            "university": item["university"],
            "country": item["country"],
            "summary": item["summary"],
            "explanation": item["explanation"],
            "_program_row": item["_program_row"],
        }

        ml_ranked.append({
            **common,
            "score": float(ml_scores[idx]),
        })

        temp_item = {
            "rule_score_norm": rule_scores_norm[idx],
            "feature_strong_pref_match": int(feature_rows[idx].get("feature_strong_pref_match", 0)),
            "feature_academic_pref_match": int(feature_rows[idx].get("feature_academic_pref_match", 0)),
            "feature_within_budget": int(feature_rows[idx].get("feature_within_budget", 0)),
        }

        ml_confidence = compute_ml_confidence(temp_item, ml_scores_norm[idx])
        ml_weight, rule_weight, _ = get_dynamic_hybrid_weights(
            favorite_profile,
            feedback_profile,
            ml_confidence=ml_confidence,
        )

        final_score = (ml_weight * ml_scores_norm[idx]) + (rule_weight * rule_scores_norm[idx])
        if is_known_positive_program(item["program_name"], item["university"], known_positive_keys):
            final_score += 0.30

        hybrid_ranked.append({
            **common,
            "score": float(final_score),
        })

    ml_ranked.sort(key=lambda x: x["score"], reverse=True)
    hybrid_ranked.sort(key=lambda x: x["score"], reverse=True)

    return ml_ranked, hybrid_ranked


def initialize_metric_store():
    return {
        "precision_scores": [],
        "hit_scores": [],
        "ndcg_scores": [],
        "evaluated_students": 0,
    }


def initialize_preference_metric_store():
    return {
        "suitability_precision_scores": [],
        "suitability_hit_scores": [],
        "avg_suitability_scores": [],
        "evaluated_students": 0,
    }


def safe_divide(a, b):
    return a / b if b else 0.0


def evaluate_program_preference_fit(program_row, prefs):
    checks = {}
    satisfied = 0
    total = 0

    total += 1
    level_match = 1 if normalize_text(program_row.get("Study Level", "")) == normalize_text(prefs.get("level", "")) else 0
    checks["level_match"] = level_match
    satisfied += level_match

    total += 1
    tuition = get_best_tuition(program_row)
    within_budget = 1 if is_budget_acceptable(prefs, tuition, hard_limit=True, program_row=program_row) else 0
    checks["within_budget"] = within_budget
    satisfied += within_budget

    total += 1
    has_detailed = bool(prefs.get("detailed_categories", []))
    if has_detailed:
        category_match = 1 if program_matches_detailed_interest(program_row, prefs) else 0
    else:
        category_match = 1 if program_matches_broad_interest(program_row, prefs) else 0
    checks["category_match"] = category_match
    satisfied += category_match

    if prefs.get("locations", []):
        total += 1
        location_match = 1 if program_matches_dimensions(program_row, prefs, require_location=True) else 0
        checks["location_match"] = location_match
        satisfied += location_match
    else:
        checks["location_match"] = None

    if prefs.get("study_modes", []):
        total += 1
        mode_match = 1 if program_matches_dimensions(program_row, prefs, require_mode=True) else 0
        checks["mode_match"] = mode_match
        satisfied += mode_match
    else:
        checks["mode_match"] = None

    if prefs.get("intensities", []):
        total += 1
        intensity_match = 1 if program_matches_dimensions(program_row, prefs, require_intensity=True) else 0
        checks["intensity_match"] = intensity_match
        satisfied += intensity_match
    else:
        checks["intensity_match"] = None

    suitability_ratio = safe_divide(satisfied, total)

    is_suitable = (
        checks["level_match"] == 1
        and checks["within_budget"] == 1
        and checks["category_match"] == 1
        and suitability_ratio >= 0.70
    )

    return suitability_ratio, is_suitable, checks


def update_metric_store(store, ranked, relevant_items, k=3, excluded_seen_items=None):
    ranked = filter_ranked_seen_items(ranked, excluded_seen_items or set())
    ranked = ranked[:k]

    recommended_keys = [
        make_program_key(item["program_name"], item["university"])
        for item in ranked
    ]

    store["precision_scores"].append(precision_at_k(recommended_keys, relevant_items, k=k))
    store["hit_scores"].append(hit_rate_at_k(recommended_keys, relevant_items, k=k))
    store["ndcg_scores"].append(ndcg_at_k(recommended_keys, relevant_items, k=k))
    store["evaluated_students"] += 1


def update_preference_metric_store(store, ranked, prefs, k=3, excluded_seen_items=None):
    ranked = filter_ranked_seen_items(ranked, excluded_seen_items or set())
    ranked = ranked[:k]

    if not ranked:
        store["suitability_precision_scores"].append(0.0)
        store["suitability_hit_scores"].append(0.0)
        store["avg_suitability_scores"].append(0.0)
        store["evaluated_students"] += 1
        return

    suitable_count = 0
    suitability_values = []

    for item in ranked:
        program_row = item.get("_program_row")
        if program_row is None:
            continue

        suitability_ratio, is_suitable, _ = evaluate_program_preference_fit(program_row, prefs)
        suitability_values.append(suitability_ratio)

        if is_suitable:
            suitable_count += 1

    suitability_precision = suitable_count / k
    suitability_hit = 1.0 if suitable_count > 0 else 0.0
    avg_suitability = float(np.mean(suitability_values)) if suitability_values else 0.0

    store["suitability_precision_scores"].append(suitability_precision)
    store["suitability_hit_scores"].append(suitability_hit)
    store["avg_suitability_scores"].append(avg_suitability)
    store["evaluated_students"] += 1


def finalize_metric_store(store):
    evaluated_students = store["evaluated_students"]

    if evaluated_students == 0:
        return {
            "students_evaluated": 0,
            "precision@3": 0.0,
            "hit_rate@3": 0.0,
            "ndcg@3": 0.0,
        }

    return {
        "students_evaluated": evaluated_students,
        "precision@3": float(np.mean(store["precision_scores"])),
        "hit_rate@3": float(np.mean(store["hit_scores"])),
        "ndcg@3": float(np.mean(store["ndcg_scores"])),
    }


def finalize_preference_metric_store(store):
    evaluated_students = store["evaluated_students"]

    if evaluated_students == 0:
        return {
            "students_evaluated": 0,
            "suitability_precision@3": 0.0,
            "suitability_hit_rate@3": 0.0,
            "avg_suitability@3": 0.0,
        }

    return {
        "students_evaluated": evaluated_students,
        "suitability_precision@3": float(np.mean(store["suitability_precision_scores"])),
        "suitability_hit_rate@3": float(np.mean(store["suitability_hit_scores"])),
        "avg_suitability@3": float(np.mean(store["avg_suitability_scores"])),
    }


def build_program_key_set(programs_df):
    return {
        make_program_key(row.get("Program Name", ""), row.get("University Name", ""))
        for _, row in programs_df.iterrows()
    }


def evaluate_all_models(
    students_df,
    programs_df,
    favorites_df,
    feedback_df,
    seen_allowed=True,
    holdout_strategy="catalog_random",
    hybrid_ml_weight=0.90,
):
    rule_store = initialize_metric_store()
    ml_store = initialize_metric_store()
    hybrid_store = initialize_metric_store()

    rule_pref_store = initialize_preference_metric_store()
    ml_pref_store = initialize_preference_metric_store()
    hybrid_pref_store = initialize_preference_metric_store()

    ground_truth_map = build_ground_truth_map(favorites_df, feedback_df)
    seen_interaction_map = build_seen_interaction_map(favorites_df, feedback_df)
    program_lookup = build_program_lookup(programs_df)
    programs_records = programs_df.to_dict("records")
    available_program_keys = build_program_key_set(programs_df)
    program_favorite_counts, program_positive_feedback_counts = build_program_popularity_maps(
        favorites_df, feedback_df
    )

    for _, student_row in students_df.iterrows():
        student_id = int(student_row["student_id"])
        relevant_items = ground_truth_map.get(student_id, set())

        if not relevant_items:
            continue

        eval_favorites_df = favorites_df
        eval_feedback_df = feedback_df
        excluded_seen_items = set()

        if not seen_allowed:
            holdout_item = choose_holdout_item(
                relevant_items,
                available_program_keys=available_program_keys,
                strategy=holdout_strategy,
                student_id=student_id,
                random_seed=42,
            )
            if holdout_item is None:
                continue

            eval_favorites_df, eval_feedback_df = remove_holdout_from_interactions(
                favorites_df,
                feedback_df,
                student_id,
                holdout_item,
            )
            excluded_seen_items = seen_interaction_map.get(student_id, set()) - {holdout_item}
            relevant_items = {holdout_item}

        prefs, favorite_profile, feedback_profile, candidates = collect_candidate_rows(
            student_row,
            programs_df,
            programs_records,
            eval_favorites_df,
            eval_feedback_df,
            program_lookup=program_lookup,
            program_favorite_counts=program_favorite_counts,
            program_positive_feedback_counts=program_positive_feedback_counts,
        )

        rule_ranked = rank_rule_only(candidates)
        ml_ranked, hybrid_ranked = rank_ml_and_hybrid(
            candidates,
            prefs,
            favorite_profile,
            feedback_profile,
            program_favorite_counts,
            program_positive_feedback_counts,
            hybrid_ml_weight=hybrid_ml_weight,
        )

        update_metric_store(rule_store, rule_ranked, relevant_items, k=3, excluded_seen_items=excluded_seen_items)
        update_metric_store(ml_store, ml_ranked, relevant_items, k=3, excluded_seen_items=excluded_seen_items)
        update_metric_store(hybrid_store, hybrid_ranked, relevant_items, k=3, excluded_seen_items=excluded_seen_items)

        update_preference_metric_store(rule_pref_store, rule_ranked, prefs, k=3, excluded_seen_items=excluded_seen_items)
        update_preference_metric_store(ml_pref_store, ml_ranked, prefs, k=3, excluded_seen_items=excluded_seen_items)
        update_preference_metric_store(hybrid_pref_store, hybrid_ranked, prefs, k=3, excluded_seen_items=excluded_seen_items)

    return {
        "rule_exact": finalize_metric_store(rule_store),
        "ml_exact": finalize_metric_store(ml_store),
        "hybrid_exact": finalize_metric_store(hybrid_store),
        "rule_pref": finalize_preference_metric_store(rule_pref_store),
        "ml_pref": finalize_preference_metric_store(ml_pref_store),
        "hybrid_pref": finalize_preference_metric_store(hybrid_pref_store),
    }


def print_exact_results(title, results):
    print("\n" + "=" * 60)
    print(title)
    print("=" * 60)
    print(f"Students evaluated: {results['students_evaluated']}")
    print(f"Precision@3: {results['precision@3']:.4f}")
    print(f"Hit Rate@3:  {results['hit_rate@3']:.4f}")
    print(f"NDCG@3:      {results['ndcg@3']:.4f}")


def print_preference_results(title, results):
    print("\n" + "=" * 60)
    print(title)
    print("=" * 60)
    print(f"Students evaluated:          {results['students_evaluated']}")
    print(f"Suitability Precision@3:    {results['suitability_precision@3']:.4f}")
    print(f"Suitability Hit Rate@3:     {results['suitability_hit_rate@3']:.4f}")
    print(f"Average Suitability@3:      {results['avg_suitability@3']:.4f}")


def build_comparison_rows(mode, all_results, hybrid_ml_weight):
    if mode == "seen_allowed":
        return [
            {
                "mode": mode,
                "model": "Rule-based",
                "hybrid_ml_weight": "",
                **all_results["rule_exact"],
            },
            {
                "mode": mode,
                "model": "ML-only",
                "hybrid_ml_weight": "",
                **all_results["ml_exact"],
            },
            {
                "mode": mode,
                "model": "Hybrid",
                "hybrid_ml_weight": "dynamic",
                **all_results["hybrid_exact"],
            },
        ]

    elif mode == "cold_start":
        return [
            {
                "mode": mode,
                "model": "Rule-based",
                "hybrid_ml_weight": "",
                **all_results["rule_pref"],
            },
            {
                "mode": mode,
                "model": "ML-only",
                "hybrid_ml_weight": "",
                **all_results["ml_pref"],
            },
            {
                "mode": mode,
                "model": "Hybrid",
                "hybrid_ml_weight": "dynamic",
                **all_results["hybrid_pref"],
            },
        ]


if __name__ == "__main__":
    programs, students, favorites, feedback = load_and_clean_data(
        PROGRAMS_PATH,
        STUDENTS_PATH,
        FAVORITES_PATH,
        FEEDBACK_PATH,
    )

    seen_results = evaluate_all_models(
        students,
        programs,
        favorites,
        feedback,
        seen_allowed=True,
    )

    unseen_results = evaluate_all_models(
        students,
        programs,
        favorites,
        feedback,
        seen_allowed=False,
        holdout_strategy="catalog_random",
    )

    print_exact_results("RULE-BASED RESULTS - SEEN ALLOWED", seen_results["rule_exact"])
    print_exact_results("ML-ONLY RESULTS - SEEN ALLOWED", seen_results["ml_exact"])
    print_exact_results("HYBRID RESULTS - SEEN ALLOWED", seen_results["hybrid_exact"])

    print_preference_results(
        "RULE-BASED PREFERENCE-SATISFACTION - COLD-START",
        unseen_results["rule_pref"],
    )
    print_preference_results(
        "ML-ONLY PREFERENCE-SATISFACTION - COLD-START",
        unseen_results["ml_pref"],
    )
    print_preference_results(
        "HYBRID PREFERENCE-SATISFACTION - COLD-START",
        unseen_results["hybrid_pref"],
    )

    comparison_rows = []
    comparison_rows.extend(build_comparison_rows("seen_allowed", seen_results, "dynamic"))
    comparison_rows.extend(build_comparison_rows("cold_start", unseen_results, "dynamic"))

    comparison_df = pd.DataFrame(comparison_rows)
    comparison_df.to_csv("model_comparison_results.csv", index=False)

    print("\nSaved comparison table to: model_comparison_results.csv")