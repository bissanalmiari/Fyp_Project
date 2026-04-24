import math
import pandas as pd
from data_preprocessing import load_and_clean_data
from feature_engineering import extract_student_preferences
from scoring import (
    score_program,
    get_best_tuition,
    get_program_rank,
    normalize_category,
    normalize_detailed_category,
    normalize_location,
    normalize_study_mode,
    normalize_intensity,
    normalize_level,
    tokenize_program_name,
)

PROGRAMS_PATH = "Programs_encoded.csv"
STUDENTS_PATH = "students_updated.csv"
FAVORITES_PATH = "favorites.csv"
FEEDBACK_PATH = "feedback.csv"
OUTPUT_PATH = "ltr_training_data.csv"


EXPLICIT_LABEL_SOURCES = {
    "favorite",
    "positive_feedback",
    "moderate_feedback",
    "weak_feedback",
    "negative_feedback",
}


def safe_float(value, default=0.0):
    try:
        if pd.isna(value):
            return default
        return float(value)
    except Exception:
        return default


def safe_int(value, default=0):
    try:
        if pd.isna(value):
            return default
        return int(value)
    except Exception:
        return default


def normalize_score_series(series: pd.Series) -> pd.Series:
    min_v = series.min()
    max_v = series.max()

    if pd.isna(min_v) or pd.isna(max_v):
        return pd.Series([0.0] * len(series), index=series.index)

    if max_v == min_v:
        return pd.Series([0.5] * len(series), index=series.index)

    return (series - min_v) / (max_v - min_v)


def get_student_favorites_lookup(student_id, favorites_df):
    if favorites_df is None or favorites_df.empty:
        return set()

    rows = favorites_df[favorites_df["student_id"] == int(student_id)].copy()
    lookup = set()

    for _, row in rows.iterrows():
        key = (
            str(row.get("program_name", "")).strip().lower(),
            str(row.get("uni_name", "")).strip().lower(),
        )
        if key[0]:
            lookup.add(key)

    return lookup


def get_student_feedback_lookup(student_id, feedback_df):
    if feedback_df is None or feedback_df.empty:
        return {}

    rows = feedback_df[feedback_df["student_id"] == int(student_id)].copy()
    lookup = {}

    for _, row in rows.iterrows():
        key = (
            str(row.get("program_name", "")).strip().lower(),
            str(row.get("uni_name", "")).strip().lower(),
        )
        if key[0]:
            lookup[key] = {
                "rating": safe_float(row.get("rating", 3), 3.0),
                "is_relevant": safe_int(row.get("is_relevant", 1), 1),
            }

    return lookup


def get_student_favorites(student_id, favorites_df):
    if favorites_df is None or favorites_df.empty:
        return []

    student_rows = favorites_df[favorites_df["student_id"] == int(student_id)].copy()
    if student_rows.empty:
        return []

    records = []
    seen = set()

    for _, row in student_rows.iterrows():
        program_name = str(row.get("program_name", "")).strip()
        uni_name = str(row.get("uni_name", "")).strip()

        key = (program_name.strip().lower(), uni_name.strip().lower())
        if not key[0]:
            continue
        if key in seen:
            continue

        seen.add(key)
        records.append({
            "program_name": program_name,
            "uni_name": uni_name,
        })

    return records


def get_student_feedback(student_id, feedback_df):
    if feedback_df is None or feedback_df.empty:
        return []

    student_rows = feedback_df[feedback_df["student_id"] == int(student_id)].copy()
    if student_rows.empty:
        return []

    records = []
    for _, row in student_rows.iterrows():
        records.append({
            "program_name": str(row.get("program_name", "")).strip(),
            "uni_name": str(row.get("uni_name", "")).strip(),
            "rating": float(row.get("rating", 3)),
            "is_relevant": int(row.get("is_relevant", 1)),
        })
    return records


def build_program_cache(programs_df: pd.DataFrame):
    cache = {}

    for idx, row in programs_df.iterrows():
        program_name = str(row.get("Program Name", "")).strip()
        university = str(row.get("University Name", "")).strip()

        cache[idx] = {
            "program_name": program_name,
            "university": university,
            "program_level": normalize_level(row.get("Study Level", "")),
            "program_location": normalize_location(row.get("Country", "")),
            "program_mode": normalize_study_mode(row.get("Study Mode", "")),
            "program_intensity": normalize_intensity(row.get("Course Intensity", "")),
            "program_broad": normalize_category(
                row.get("broad_category", row.get("Program Category", ""))
            ),
            "program_detailed": normalize_detailed_category(row.get("detailed_category", "")),
            "tuition": get_best_tuition(row),
            "rank": get_program_rank(row),
            "program_tokens": tokenize_program_name(program_name),
        }

    return cache


def build_program_lookup(programs_df: pd.DataFrame):
    lookup = {}

    for _, row in programs_df.iterrows():
        key = (
            str(row.get("Program Name", "")).strip().lower(),
            str(row.get("University Name", "")).strip().lower(),
        )
        lookup.setdefault(key, []).append(row)

    return lookup


def build_program_popularity_maps(favorites_df, feedback_df):
    favorite_counts = {}
    positive_feedback_counts = {}

    if favorites_df is not None and not favorites_df.empty:
        fav_group = (
            favorites_df.groupby(["program_name", "uni_name"])
            .size()
            .reset_index(name="cnt")
        )
        for _, row in fav_group.iterrows():
            key = (
                str(row["program_name"]).strip().lower(),
                str(row["uni_name"]).strip().lower(),
            )
            favorite_counts[key] = int(row["cnt"])

    if feedback_df is not None and not feedback_df.empty:
        positive_rows = feedback_df[
            (feedback_df["rating"] >= 4) | (feedback_df["is_relevant"] == 1)
        ].copy()

        if not positive_rows.empty:
            pos_group = (
                positive_rows.groupby(["program_name", "uni_name"])
                .size()
                .reset_index(name="cnt")
            )
            for _, row in pos_group.iterrows():
                key = (
                    str(row["program_name"]).strip().lower(),
                    str(row["uni_name"]).strip().lower(),
                )
                positive_feedback_counts[key] = int(row["cnt"])

    return favorite_counts, positive_feedback_counts


def build_favorite_profile(student_id, favorites_df, program_lookup):
    favorite_records = get_student_favorites(student_id, favorites_df)

    profile = {
        "favorite_records": favorite_records,
        "categories": set(),
        "detailed_categories": set(),
        "name_tokens": set(),
    }

    if not favorite_records:
        return profile

    matched_rows = []

    for fav in favorite_records:
        fav_program = str(fav["program_name"]).strip().lower()
        fav_uni = str(fav["uni_name"]).strip().lower()
        matched_rows.extend(program_lookup.get((fav_program, fav_uni), []))

    for fav in favorite_records:
        profile["name_tokens"].update(tokenize_program_name(fav["program_name"]))

    for program in matched_rows:
        profile["categories"].add(
            normalize_category(program.get("broad_category", program.get("Program Category", "")))
        )
        profile["detailed_categories"].add(
            normalize_detailed_category(program.get("detailed_category", ""))
        )
        profile["name_tokens"].update(tokenize_program_name(program.get("Program Name", "")))

    return profile


def build_feedback_profile(student_id, feedback_df, program_lookup):
    feedback_records = get_student_feedback(student_id, feedback_df)

    profile = {
        "feedback_records": feedback_records,
        "categories": {},
        "detailed_categories": {},
        "locations": {},
        "study_modes": {},
        "intensities": {},
        "name_tokens_positive": set(),
        "name_tokens_negative": set(),
    }

    if not feedback_records:
        return profile

    def update_stats(container, key, rating, is_relevant):
        if not key:
            return
        if key not in container:
            container[key] = {"count": 0, "rating_sum": 0.0, "relevant_sum": 0}
        container[key]["count"] += 1.0
        container[key]["rating_sum"] += float(rating)
        container[key]["relevant_sum"] += int(is_relevant)

    for fb in feedback_records:
        fb_program = str(fb["program_name"]).strip().lower()
        fb_uni = str(fb["uni_name"]).strip().lower()
        rating = float(fb["rating"])
        is_relevant = int(fb["is_relevant"])

        if rating >= 4 or is_relevant == 1:
            profile["name_tokens_positive"].update(tokenize_program_name(fb["program_name"]))
        if rating <= 2 or is_relevant == 0:
            profile["name_tokens_negative"].update(tokenize_program_name(fb["program_name"]))

        matched_rows = program_lookup.get((fb_program, fb_uni), [])

        for program in matched_rows:
            broad = normalize_category(program.get("broad_category", program.get("Program Category", "")))
            detailed = normalize_detailed_category(program.get("detailed_category", ""))
            location = normalize_location(program.get("Country", ""))
            study_mode = str(program.get("Study Mode", "")).strip().lower()
            intensity = str(program.get("Course Intensity", "")).strip().lower()

            update_stats(profile["categories"], broad, rating, is_relevant)
            update_stats(profile["detailed_categories"], detailed, rating, is_relevant)
            update_stats(profile["locations"], location, rating, is_relevant)
            update_stats(profile["study_modes"], study_mode, rating, is_relevant)
            update_stats(profile["intensities"], intensity, rating, is_relevant)

    return profile


def compute_manual_features(
    student_prefs,
    program_row,
    program_cached,
    favorite_profile=None,
    feedback_profile=None,
    program_favorite_counts=None,
    program_positive_feedback_counts=None,
):
    program_name = program_cached["program_name"]
    university = program_cached["university"]

    program_level = program_cached["program_level"]
    program_location = program_cached["program_location"]
    program_mode = program_cached["program_mode"]
    program_intensity = program_cached["program_intensity"]
    program_broad = program_cached["program_broad"]
    program_detailed = program_cached["program_detailed"]
    tuition = program_cached["tuition"]
    rank = program_cached["rank"]
    program_tokens = program_cached["program_tokens"]

    student_locations = set(student_prefs.get("locations", []))
    student_modes = set(student_prefs.get("study_modes", []))
    student_intensities = set(student_prefs.get("intensities", []))
    student_categories = set(student_prefs.get("categories", []))
    student_detailed = set(student_prefs.get("detailed_categories", []))
    student_level = student_prefs.get("level", "")
    budget_min = safe_float(student_prefs.get("budget_min", 0), 0.0)
    budget_max = safe_float(student_prefs.get("budget_max", 999999), 999999.0)

    fav_tokens = set()
    fav_categories = set()
    fav_detailed = set()

    if favorite_profile:
        fav_tokens = set(favorite_profile.get("name_tokens", set()))
        fav_categories = set(favorite_profile.get("categories", set()))
        fav_detailed = set(favorite_profile.get("detailed_categories", set()))

    feedback_pos_tokens = set()
    feedback_neg_tokens = set()
    if feedback_profile:
        feedback_pos_tokens = set(feedback_profile.get("name_tokens_positive", set()))
        feedback_neg_tokens = set(feedback_profile.get("name_tokens_negative", set()))

    level_match = 1 if program_level == student_level else 0
    location_match = 1 if program_location in student_locations else 0
    mode_match = 1 if program_mode in student_modes else 0
    intensity_match = 1 if program_intensity in student_intensities else 0
    broad_category_match = 1 if program_broad in student_categories else 0
    detailed_category_match = 1 if program_detailed in student_detailed else 0

    tuition_missing = 1 if tuition is None else 0
    within_budget = 1 if tuition is None or tuition <= budget_max else 0
    below_budget_min = 1 if tuition is not None and tuition < budget_min else 0

    budget_distance_over = 0.0
    budget_distance_under = 0.0
    if tuition is not None:
        budget_distance_over = max(0.0, tuition - budget_max)
        budget_distance_under = max(0.0, budget_min - tuition)

    rank_available = 1 if rank is not None else 0
    rank_score = 0.0 if rank is None else 1.0 / (1.0 + rank)
    top_50_uni = 1 if rank is not None and rank <= 50 else 0
    top_100_uni = 1 if rank is not None and rank <= 100 else 0
    top_300_uni = 1 if rank is not None and rank <= 300 else 0

    favorite_token_overlap = len(program_tokens & fav_tokens)
    favorite_broad_overlap = 1 if program_broad in fav_categories else 0
    favorite_detailed_overlap = 1 if program_detailed in fav_detailed else 0

    positive_feedback_token_overlap = len(program_tokens & feedback_pos_tokens)
    negative_feedback_token_overlap = len(program_tokens & feedback_neg_tokens)

    req_ielts = safe_float(program_row.get("IELTS"), -1)
    req_toefl = safe_float(program_row.get("TOEFL"), -1)
    req_sat = safe_float(program_row.get("SAT"), -1)
    req_gpa = safe_float(program_row.get("GPA"), -1)

    student_ielts = safe_float(student_prefs.get("ielts"), -1)
    student_toefl = safe_float(student_prefs.get("toefl"), -1)
    student_sat = safe_float(student_prefs.get("sat"), -1)
    student_gpa = safe_float(student_prefs.get("gpa"), -1)

    meets_ielts = 1 if req_ielts >= 0 and student_ielts >= req_ielts else 0
    meets_toefl = 1 if req_toefl >= 0 and student_toefl >= req_toefl else 0
    meets_sat = 1 if req_sat >= 0 and student_sat >= req_sat else 0
    meets_gpa = 1 if req_gpa >= 0 and student_gpa >= req_gpa else 0

    has_any_exam_requirement = 1 if (req_ielts >= 0 or req_toefl >= 0 or req_sat >= 0) else 0
    meets_any_exam_requirement = 1 if (meets_ielts or meets_toefl or meets_sat) else 0

    rule_score, _, _ = score_program(
        student_prefs,
        program_row,
        favorite_profile=favorite_profile,
        feedback_profile=feedback_profile,
    )

    strong_pref_match = 1 if (level_match and location_match and broad_category_match) else 0
    academic_pref_match = 1 if (level_match and (broad_category_match or detailed_category_match)) else 0
    budget_rank_combo = float(within_budget) * float(rank_score)
    favorite_category_combo = favorite_broad_overlap * broad_category_match
    favorite_detailed_combo = favorite_detailed_overlap * detailed_category_match
    exam_and_level_combo = meets_any_exam_requirement * level_match
    location_category_combo = location_match * broad_category_match

    program_key = (program_name.strip().lower(), university.strip().lower())
    favorite_count_global = 0
    positive_feedback_count_global = 0

    if program_favorite_counts:
        favorite_count_global = int(program_favorite_counts.get(program_key, 0))
    if program_positive_feedback_counts:
        positive_feedback_count_global = int(program_positive_feedback_counts.get(program_key, 0))

    total_popularity = favorite_count_global + positive_feedback_count_global
    popularity_log = math.log1p(total_popularity)

    return {
        "program_name": program_name,
        "university": university,
        "country": str(program_row.get("Country", "")).strip(),
        "study_level": str(program_row.get("Study Level", "")).strip(),
        "study_mode": str(program_row.get("Study Mode", "")).strip(),
        "course_intensity": str(program_row.get("Course Intensity", "")).strip(),
        "broad_category": str(program_row.get("broad_category", program_row.get("Program Category", ""))).strip(),
        "detailed_category": str(program_row.get("detailed_category", "")).strip(),

        "feature_level_match": level_match,
        "feature_location_match": location_match,
        "feature_mode_match": mode_match,
        "feature_intensity_match": intensity_match,
        "feature_broad_category_match": broad_category_match,
        "feature_detailed_category_match": detailed_category_match,

        "feature_tuition_missing": tuition_missing,
        "feature_within_budget": within_budget,
        "feature_below_budget_min": below_budget_min,
        "feature_budget_distance_over": budget_distance_over,
        "feature_budget_distance_under": budget_distance_under,
        "feature_tuition_value": -1 if tuition is None else float(tuition),

        "feature_rank_available": rank_available,
        "feature_rank_value": -1 if rank is None else float(rank),
        "feature_rank_score": rank_score,
        "feature_top_50_uni": top_50_uni,
        "feature_top_100_uni": top_100_uni,
        "feature_top_300_uni": top_300_uni,

        "feature_favorite_token_overlap": favorite_token_overlap,
        "feature_favorite_broad_overlap": favorite_broad_overlap,
        "feature_favorite_detailed_overlap": favorite_detailed_overlap,

        "feature_positive_feedback_token_overlap": positive_feedback_token_overlap,
        "feature_negative_feedback_token_overlap": negative_feedback_token_overlap,

        "feature_has_any_exam_requirement": has_any_exam_requirement,
        "feature_meets_any_exam_requirement": meets_any_exam_requirement,
        "feature_meets_ielts": meets_ielts,
        "feature_meets_toefl": meets_toefl,
        "feature_meets_sat": meets_sat,
        "feature_meets_gpa": meets_gpa,

        "feature_rule_score_raw": float(rule_score),

        "feature_strong_pref_match": strong_pref_match,
        "feature_academic_pref_match": academic_pref_match,
        "feature_budget_rank_combo": budget_rank_combo,
        "feature_favorite_category_combo": favorite_category_combo,
        "feature_favorite_detailed_combo": favorite_detailed_combo,
        "feature_exam_and_level_combo": exam_and_level_combo,
        "feature_location_category_combo": location_category_combo,

        "feature_program_favorite_count_global": favorite_count_global,
        "feature_program_positive_feedback_count_global": positive_feedback_count_global,
        "feature_program_total_popularity": total_popularity,
        "feature_popularity_log": popularity_log,
    }


def build_relevance_label(student_id, program_row, favorites_lookup, feedback_lookup):
    key = (
        str(program_row.get("Program Name", "")).strip().lower(),
        str(program_row.get("University Name", "")).strip().lower(),
    )

    if key in favorites_lookup:
        return 4, "favorite"

    if key in feedback_lookup:
        row = feedback_lookup[key]
        rating = safe_float(row.get("rating", 3), 3.0)
        is_relevant = safe_int(row.get("is_relevant", 1), 1)

        if is_relevant == 0 or rating <= 2:
            return 0, "negative_feedback"

        if rating >= 4 and is_relevant == 1:
            return 3, "positive_feedback"

        if rating >= 3 and is_relevant == 1:
            return 2, "moderate_feedback"

        return 1, "weak_feedback"

    return 1, "unlabeled_candidate"


def should_keep_candidate(label_source, features, min_unlabeled_rule_score=4.5):
    if label_source in EXPLICIT_LABEL_SOURCES:
        return True

    rule_score = float(features.get("feature_rule_score_raw", 0.0))
    within_budget = int(features.get("feature_within_budget", 0))
    academic_pref = int(features.get("feature_academic_pref_match", 0))
    location_match = int(features.get("feature_location_match", 0))
    strong_pref = int(features.get("feature_strong_pref_match", 0))
    detailed_match = int(features.get("feature_detailed_category_match", 0))
    broad_match = int(features.get("feature_broad_category_match", 0))
    popularity = int(features.get("feature_program_total_popularity", 0))

    if rule_score < min_unlabeled_rule_score and popularity == 0:
        return False

    if not within_budget and popularity == 0:
        return False

    if not (academic_pref or location_match or strong_pref or detailed_match or broad_match or popularity > 0):
        return False

    return True


def assign_weak_supervision_labels(student_group: pd.DataFrame) -> pd.DataFrame:
    group = student_group.copy()

    unlabeled_mask = ~group["label_source"].isin(EXPLICIT_LABEL_SOURCES)
    if not unlabeled_mask.any():
        return group

    unlabeled = group.loc[unlabeled_mask].copy()

    if "feature_rule_score_norm" not in unlabeled.columns:
        unlabeled["feature_rule_score_norm"] = normalize_score_series(unlabeled["feature_rule_score_raw"])

    popularity = unlabeled.get(
        "feature_program_total_popularity",
        pd.Series([0] * len(unlabeled), index=unlabeled.index)
    )

    truly_bad_mask = (
        (unlabeled["feature_rule_score_raw"] <= 0)
        | (
            (unlabeled["feature_level_match"] == 0)
            & (unlabeled["feature_rule_score_norm"] < 0.10)
        )
        | (
            (unlabeled["feature_broad_category_match"] == 0)
            & (unlabeled["feature_detailed_category_match"] == 0)
            & (unlabeled["feature_rule_score_norm"] < 0.10)
            & (popularity <= 0)
        )
        | (
            (unlabeled["feature_within_budget"] == 0)
            & (unlabeled["feature_tuition_missing"] == 0)
            & (unlabeled["feature_rule_score_norm"] < 0.10)
            & (popularity <= 0)
        )
    )

    bad_rows = unlabeled.loc[truly_bad_mask].copy()
    neutral_plus = unlabeled.loc[~truly_bad_mask].copy()

    group.loc[bad_rows.index, "label"] = 0
    group.loc[bad_rows.index, "label_source"] = "weak_supervision_bad_rule"

    if neutral_plus.empty:
        return group

    neutral_plus = neutral_plus.sort_values(
        by=[
            "feature_rule_score_norm",
            "feature_popularity_log",
            "feature_program_total_popularity",
            "feature_rule_score_raw",
        ],
        ascending=[False, False, False, False],
    ).copy()

    neutral_plus["weak_rank_position"] = range(1, len(neutral_plus) + 1)

    top_n_good = max(6, int(len(neutral_plus) * 0.15))

    def weak_label(row):
        pos = int(row["weak_rank_position"])
        norm_score = float(row["feature_rule_score_norm"])
        strong_pref = int(row.get("feature_strong_pref_match", 0))
        within_budget = int(row.get("feature_within_budget", 0))
        academic_pref = int(row.get("feature_academic_pref_match", 0))
        popularity_local = int(row.get("feature_program_total_popularity", 0))
        popularity_log = float(row.get("feature_popularity_log", 0.0))

        if (
            ((pos <= top_n_good) or (norm_score >= 0.80) or (popularity_local >= 2) or (popularity_log >= 1.0))
            and (academic_pref or strong_pref)
            and within_budget
        ):
            return 2, "weak_supervision_good_rule"

        if norm_score < 0.45 and popularity_local == 0:
            return 0, "weak_supervision_bad_rule"

        return 1, "weak_supervision_neutral_rule"

    assignments = neutral_plus.apply(weak_label, axis=1, result_type="expand")
    assignments.columns = ["new_label", "new_source"]

    group.loc[neutral_plus.index, "label"] = assignments["new_label"].values
    group.loc[neutral_plus.index, "label_source"] = assignments["new_source"].values

    return group


def balance_training_data(
    df: pd.DataFrame,
    negative_ratio: float = 0.5,
    random_state: int = 42,
) -> pd.DataFrame:
    df = df.copy()

    zero = df[df["label"] == 0].copy()
    label1 = df[df["label"] == 1].copy()
    strong = df[df["label"] >= 2].copy()

    if strong.empty or zero.empty:
        return df.sample(frac=1, random_state=random_state).reset_index(drop=True)

    target_zero_count = int((len(label1) + len(strong)) * negative_ratio)
    target_zero_count = max(target_zero_count, len(strong))

    if len(zero) > target_zero_count:
        zero = zero.sample(n=target_zero_count, random_state=random_state)

    max_label1 = max(1, int(len(strong) * 1.8))

    if len(label1) > max_label1:
        label1 = label1.sample(n=max_label1, random_state=random_state)

    balanced = pd.concat([zero, label1, strong], ignore_index=True)
    balanced = balanced.sample(frac=1, random_state=random_state).reset_index(drop=True)

    return balanced


def build_ltr_training_data(
    programs_path=PROGRAMS_PATH,
    students_path=STUDENTS_PATH,
    favorites_path=FAVORITES_PATH,
    feedback_path=FEEDBACK_PATH,
    output_path=OUTPUT_PATH,
    max_programs_per_student=None,
    keep_only_budget_valid=False,
    balance_dataset=True,
    negative_ratio=0.5,
    min_unlabeled_rule_score=4.5,
):
    programs_df, students_df, favorites_df, feedback_df = load_and_clean_data(
        programs_path,
        students_path,
        favorites_path,
        feedback_path,
    )

    program_cache = build_program_cache(programs_df)
    program_lookup = build_program_lookup(programs_df)
    program_favorite_counts, program_positive_feedback_counts = build_program_popularity_maps(
        favorites_df, feedback_df
    )

    rows = []

    for _, student_row in students_df.iterrows():
        student_id = int(student_row["student_id"])
        student_prefs = extract_student_preferences(student_row)

        favorite_profile = build_favorite_profile(student_id, favorites_df, program_lookup)
        feedback_profile = build_feedback_profile(student_id, feedback_df, program_lookup)

        favorites_lookup = get_student_favorites_lookup(student_id, favorites_df)
        feedback_lookup = get_student_feedback_lookup(student_id, feedback_df)

        student_programs = programs_df

        if keep_only_budget_valid:
            budget_max = safe_float(student_prefs.get("budget_max", 999999), 999999.0)
            keep_indices = []

            for idx, row in student_programs.iterrows():
                tuition = program_cache[idx]["tuition"]
                if tuition is None or tuition <= budget_max:
                    keep_indices.append(idx)

            student_programs = student_programs.loc[keep_indices]

        if max_programs_per_student is not None:
            student_programs = student_programs.head(max_programs_per_student)

        for idx, program_row in student_programs.iterrows():
            features = compute_manual_features(
                student_prefs,
                program_row,
                program_cache[idx],
                favorite_profile=favorite_profile,
                feedback_profile=feedback_profile,
                program_favorite_counts=program_favorite_counts,
                program_positive_feedback_counts=program_positive_feedback_counts,
            )

            relevance_label, label_source = build_relevance_label(
                student_id,
                program_row,
                favorites_lookup,
                feedback_lookup,
            )

            if not should_keep_candidate(
                label_source=label_source,
                features=features,
                min_unlabeled_rule_score=min_unlabeled_rule_score,
            ):
                continue

            row = {
                "student_id": student_id,
                "query_id": student_id,
                "label": relevance_label,
                "label_source": label_source,
                "student_level": student_prefs.get("level", ""),
                "student_budget_min": safe_float(student_prefs.get("budget_min", 0), 0.0),
                "student_budget_max": safe_float(student_prefs.get("budget_max", 999999), 999999.0),
                "student_locations_count": len(student_prefs.get("locations", [])),
                "student_modes_count": len(student_prefs.get("study_modes", [])),
                "student_intensities_count": len(student_prefs.get("intensities", [])),
                "student_categories_count": len(student_prefs.get("categories", [])),
                "student_detailed_categories_count": len(student_prefs.get("detailed_categories", [])),
            }
            row.update(features)
            rows.append(row)

    training_df = pd.DataFrame(rows)

    if training_df.empty:
        raise ValueError("Training data is empty after filtering. Lower min_unlabeled_rule_score.")

    training_df["feature_rule_score_norm"] = (
        training_df.groupby("student_id")["feature_rule_score_raw"]
        .transform(normalize_score_series)
    )

    training_df["original_label"] = training_df["label"]
    training_df["original_label_source"] = training_df["label_source"]

    training_df = (
        training_df.groupby("student_id", group_keys=False)[list(training_df.columns)]
        .apply(assign_weak_supervision_labels)
        .reset_index(drop=True)
    )

    if balance_dataset:
        training_df = balance_training_data(
            training_df,
            negative_ratio=negative_ratio,
            random_state=42,
        )

    training_df["binary_label"] = (training_df["label"] >= 3).astype(int)

    training_df = training_df.sort_values(
        by=["student_id", "label", "feature_rule_score_raw"],
        ascending=[True, False, False],
    ).reset_index(drop=True)

    if output_path:
        training_df.to_csv(output_path, index=False)

    return training_df


if __name__ == "__main__":
    df = build_ltr_training_data(
        programs_path=PROGRAMS_PATH,
        students_path=STUDENTS_PATH,
        favorites_path=FAVORITES_PATH,
        feedback_path=FEEDBACK_PATH,
        output_path=OUTPUT_PATH,
        max_programs_per_student=None,
        keep_only_budget_valid=False,
        balance_dataset=True,
        negative_ratio=0.5,
        min_unlabeled_rule_score=4.5,
    )

    print("Training data created successfully.")
    print("Shape:", df.shape)

    print("\nLabel distribution:")
    print(df["label"].value_counts(dropna=False).sort_index())

    print("\nLabel source distribution:")
    print(df["label_source"].value_counts(dropna=False).head(20))

    print("\nSample rows:")
    preview_cols = [
        "student_id",
        "program_name",
        "university",
        "label",
        "label_source",
        "original_label",
        "original_label_source",
        "feature_rule_score_raw",
        "feature_rule_score_norm",
        "feature_level_match",
        "feature_location_match",
        "feature_broad_category_match",
        "feature_detailed_category_match",
        "feature_within_budget",
        "feature_rank_value",
        "feature_strong_pref_match",
        "feature_budget_rank_combo",
        "feature_program_total_popularity",
        "feature_popularity_log",
    ]
    print(df[preview_cols].head(10).to_string(index=False))