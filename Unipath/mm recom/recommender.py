from feature_engineering import (
    extract_student_preferences,
    normalize_location,
    normalize_program_name,
)
from scoring import (
    score_program,
    get_best_tuition,
    is_budget_acceptable,
    normalize_level,
    normalize_category,
    get_program_keywords,
    normalize_text,
    tokenize_program_name,
    normalize_detailed_category,
)

import math
import pandas as pd

try:
    from ml_ranker import load_ranker, predict_ml_scores
except Exception:
    load_ranker = None
    predict_ml_scores = None


_RANKER_MODEL = None
_RANKER_FEATURE_COLS = None
_RANKER_LOADED = False


def get_cached_ranker():
    global _RANKER_MODEL, _RANKER_FEATURE_COLS, _RANKER_LOADED

    if _RANKER_LOADED:
        return _RANKER_MODEL, _RANKER_FEATURE_COLS

    _RANKER_LOADED = True

    if load_ranker is None:
        _RANKER_MODEL = None
        _RANKER_FEATURE_COLS = None
        return None, None

    try:
        _RANKER_MODEL, _RANKER_FEATURE_COLS = load_ranker()
    except Exception:
        _RANKER_MODEL = None
        _RANKER_FEATURE_COLS = None

    return _RANKER_MODEL, _RANKER_FEATURE_COLS


def normalize_university_name(value: str) -> str:
    return normalize_text(value)


def normalize_program_family(value: str) -> str:
    return normalize_program_name(value)


def build_program_lookup(programs_df):
    lookup = {}

    for _, row in programs_df.iterrows():
        key = (
            normalize_program_name(row.get("Program Name", "")),
            normalize_university_name(row.get("University Name", "")),
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
                normalize_program_name(row["program_name"]),
                normalize_university_name(row["uni_name"]),
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
                    normalize_program_name(row["program_name"]),
                    normalize_university_name(row["uni_name"]),
                )
                positive_feedback_counts[key] = int(row["cnt"])

    return favorite_counts, positive_feedback_counts


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

        key = (normalize_program_name(program_name), normalize_university_name(uni_name))
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


def build_favorite_profile(student_id, favorites_df, programs_df, program_lookup=None):
    favorite_records = get_student_favorites(student_id, favorites_df)

    profile = {
        "favorite_records": favorite_records,
        "categories": set(),
        "detailed_categories": set(),
        "name_tokens": set(),
    }

    if not favorite_records:
        return profile

    if program_lookup is None:
        program_lookup = build_program_lookup(programs_df)

    matched_rows = []

    for fav in favorite_records:
        fav_program = normalize_program_name(fav["program_name"])
        fav_uni = normalize_university_name(fav["uni_name"])
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


def build_feedback_profile(student_id, feedback_df, programs_df, program_lookup=None):
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

    if program_lookup is None:
        program_lookup = build_program_lookup(programs_df)

    def update_stats(container, key, rating, is_relevant):
        if not key:
            return
        if key not in container:
            container[key] = {"count": 0, "rating_sum": 0.0, "relevant_sum": 0}
        container[key]["count"] += 1
        container[key]["rating_sum"] += float(rating)
        container[key]["relevant_sum"] += int(is_relevant)

    for fb in feedback_records:
        fb_program = normalize_program_name(fb["program_name"])
        fb_uni = normalize_university_name(fb["uni_name"])
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
            study_mode = normalize_text(program.get("Study Mode", ""))
            intensity = normalize_text(program.get("Course Intensity", ""))

            update_stats(profile["categories"], broad, rating, is_relevant)
            update_stats(profile["detailed_categories"], detailed, rating, is_relevant)
            update_stats(profile["locations"], location, rating, is_relevant)
            update_stats(profile["study_modes"], study_mode, rating, is_relevant)
            update_stats(profile["intensities"], intensity, rating, is_relevant)

    return profile


def program_matches_detailed_interest(program_row, prefs):
    student_detailed = prefs.get("detailed_categories", [])
    if not student_detailed:
        return False

    program_detailed = normalize_detailed_category(program_row.get("detailed_category", ""))
    return program_detailed in student_detailed


def program_matches_broad_interest(program_row, prefs):
    student_categories = prefs.get("categories", [])
    if not student_categories:
        return True

    program_category = normalize_category(
        program_row.get("broad_category", program_row.get("Program Category", ""))
    )
    detected_keywords = get_program_keywords(program_row)

    if program_category in student_categories:
        return True

    if any(cat in student_categories for cat in detected_keywords):
        return True

    return False


def program_matches_near_category(program_row, prefs):
    student_categories = prefs.get("categories", [])
    if not student_categories:
        return True

    program_category = normalize_category(
        program_row.get("broad_category", program_row.get("Program Category", ""))
    )
    detected_keywords = get_program_keywords(program_row)

    close_matches = {
        "engineering": {"computing & technology", "data science & artificial intelligence", "transport, aviation & maritime"},
        "computing & technology": {"engineering", "data science & artificial intelligence"},
        "data science & artificial intelligence": {"computing & technology", "natural & physical sciences"},
        "health & medicine": {"life sciences", "psychology & behavioral sciences"},
        "life sciences": {"health & medicine", "natural & physical sciences"},
        "natural & physical sciences": {"life sciences", "data science & artificial intelligence", "engineering", "environment & agriculture"},
        "social sciences": {"psychology & behavioral sciences", "business & management", "law & governance", "education"},
        "psychology & behavioral sciences": {"social sciences", "health & medicine", "education"},
        "business & management": {"social sciences", "hospitality & tourism", "sports & events"},
        "law & governance": {"social sciences", "business & management"},
        "education": {"social sciences", "psychology & behavioral sciences", "humanities & languages"},
        "arts, design & architecture": {"media & communication", "humanities & languages"},
        "humanities & languages": {"education", "arts, design & architecture", "media & communication"},
        "environment & agriculture": {"life sciences", "natural & physical sciences", "engineering"},
        "media & communication": {"arts, design & architecture", "humanities & languages", "business & management"},
        "hospitality & tourism": {"business & management", "sports & events"},
        "sports & events": {"hospitality & tourism", "business & management", "health & medicine"},
        "transport, aviation & maritime": {"engineering", "business & management"},
        "interdisciplinary & emerging fields": {
            "engineering", "computing & technology", "data science & artificial intelligence",
            "business & management", "environment & agriculture"
        },
    }

    for student_cat in student_categories:
        if program_category == student_cat:
            return True

        if program_category in close_matches.get(student_cat, set()):
            return True

        if any(cat == student_cat for cat in detected_keywords):
            return True

        if any(cat in close_matches.get(student_cat, set()) for cat in detected_keywords):
            return True

    return False


def program_matches_dimensions(program_row, prefs, require_location=False, require_mode=False, require_intensity=False):
    program_location = normalize_location(program_row.get("Country", ""))
    program_mode = normalize_text(program_row.get("Study Mode", ""))
    program_intensity = normalize_text(program_row.get("Course Intensity", ""))

    preferred_locations = prefs.get("locations", [])
    preferred_modes = prefs.get("study_modes", [])
    preferred_intensities = prefs.get("intensities", [])

    if require_location and preferred_locations and program_location not in preferred_locations:
        return False

    if require_mode and preferred_modes and program_mode not in preferred_modes:
        return False

    if require_intensity and preferred_intensities and program_intensity not in preferred_intensities:
        return False

    return True


def filter_candidates(
    programs_df,
    prefs,
    strict_location=True,
    hard_budget=True,
    use_detailed=False,
    allow_near_category=False,
    require_mode=False,
    require_intensity=False,
):
    candidates = []

    for _, program in programs_df.iterrows():
        tuition = get_best_tuition(program)

        program_level = normalize_level(program.get("Study Level", ""))
        student_level = prefs.get("level", "")

        # strict academic progression / target level filter
        if student_level == "bachelor" and program_level != "bachelor":
            continue

        if student_level == "master" and program_level != "master":
            continue

        if not is_budget_acceptable(prefs, tuition, hard_limit=hard_budget, program_row=program):
            continue

        if use_detailed:
            if not program_matches_detailed_interest(program, prefs):
                continue
        else:
            if allow_near_category:
                if not program_matches_near_category(program, prefs):
                    continue
            else:
                if not program_matches_broad_interest(program, prefs):
                    continue

        if not program_matches_dimensions(
            program,
            prefs,
            require_location=strict_location,
            require_mode=require_mode,
            require_intensity=require_intensity,
        ):
            continue

        candidates.append(program)

    return candidates


def safe_float(value, default=None):
    try:
        if value is None:
            return default
        value = float(value)
        if math.isnan(value):
            return default
        return value
    except Exception:
        return default


def minmax_normalize(values):
    if not values:
        return []

    values = [safe_float(v, 0.0) for v in values]
    vmin = min(values)
    vmax = max(values)

    if vmax - vmin == 0:
        return [1.0 for _ in values]

    return [(v - vmin) / (vmax - vmin) for v in values]


def get_rank_value(program_row):
    rank = safe_float(program_row.get("Rank"), None)
    if rank is None or rank <= 0:
        return -1.0
    return rank


def get_rank_score(program_row):
    rank = safe_float(program_row.get("Rank"), None)
    if rank is None or rank <= 0:
        return 0.0
    return 1.0 / (1.0 + rank)


def normalize_candidate_rule_scores(feature_rows):
    if not feature_rows:
        return feature_rows

    norm_scores = minmax_normalize([
        row.get("feature_rule_score_raw", 0.0)
        for row in feature_rows
    ])

    for idx, row in enumerate(feature_rows):
        row["feature_rule_score_norm"] = norm_scores[idx]

    return feature_rows


def build_known_positive_program_keys(favorite_profile=None, feedback_profile=None):
    known_positive_keys = set()

    if favorite_profile:
        for record in favorite_profile.get("favorite_records", []):
            key = (
                normalize_program_name(record.get("program_name", "")),
                normalize_university_name(record.get("uni_name", "")),
            )
            if key[0]:
                known_positive_keys.add(key)

    if feedback_profile:
        for record in feedback_profile.get("feedback_records", []):
            rating = safe_float(record.get("rating"), 0.0)
            is_relevant = int(safe_float(record.get("is_relevant"), 0) or 0)

            if rating >= 4 or is_relevant == 1:
                key = (
                    normalize_program_name(record.get("program_name", "")),
                    normalize_university_name(record.get("uni_name", "")),
                )
                if key[0]:
                    known_positive_keys.add(key)

    return known_positive_keys


def is_known_positive_program(program_name, university, known_positive_keys):
    key = (
        normalize_program_name(program_name),
        normalize_university_name(university),
    )
    return key in known_positive_keys


def build_seen_program_keys(favorites_df=None, feedback_df=None, student_id=None):
    seen_keys = set()

    if student_id is None:
        return seen_keys

    if favorites_df is not None and not favorites_df.empty:
        student_favorites = favorites_df[favorites_df["student_id"] == int(student_id)]
        for _, row in student_favorites.iterrows():
            key = (
                normalize_program_name(row.get("program_name", "")),
                normalize_university_name(row.get("uni_name", "")),
            )
            if key[0]:
                seen_keys.add(key)

    if feedback_df is not None and not feedback_df.empty:
        student_feedback = feedback_df[feedback_df["student_id"] == int(student_id)]
        for _, row in student_feedback.iterrows():
            key = (
                normalize_program_name(row.get("program_name", "")),
                normalize_university_name(row.get("uni_name", "")),
            )
            if key[0]:
                seen_keys.add(key)

    return seen_keys


def remove_seen_programs(ranked_items, seen_program_keys):
    if not seen_program_keys:
        return ranked_items

    filtered = []
    for item in ranked_items:
        key = (
            normalize_program_name(item["program_name"]),
            normalize_university_name(item["university"]),
        )
        if key in seen_program_keys:
            continue
        filtered.append(item)

    return filtered


def meets_any_exam_requirement(student_prefs, program_row):
    req_ielts = safe_float(program_row.get("IELTS"), None)
    req_toefl = safe_float(program_row.get("TOEFL"), None)
    req_sat = safe_float(program_row.get("SAT"), None)
    req_gpa = safe_float(program_row.get("GPA"), None)

    student_ielts = safe_float(student_prefs.get("ielts"), None)
    student_toefl = safe_float(student_prefs.get("toefl"), None)
    student_sat = safe_float(student_prefs.get("sat"), None)
    student_gpa = safe_float(student_prefs.get("gpa"), None)

    checks = []

    if req_ielts is not None:
        checks.append(student_ielts is not None and student_ielts >= req_ielts)
    if req_toefl is not None:
        checks.append(student_toefl is not None and student_toefl >= req_toefl)
    if req_sat is not None:
        checks.append(student_sat is not None and student_sat >= req_sat)

    if not checks:
        return 0

    return 1 if any(checks) else 0


def meets_requirement(student_value, required_value):
    return 1 if required_value is not None and student_value is not None and student_value >= required_value else 0


def get_feedback_signal(container, key):
    if not key or key not in container:
        return 0.0

    stats = container[key]
    count = stats.get("count", 0)
    if count == 0:
        return 0.0

    avg_rating = stats["rating_sum"] / count
    relevance_rate = stats["relevant_sum"] / count

    if avg_rating >= 4.0 and relevance_rate >= 0.6:
        return 1.0

    if avg_rating <= 2.5 and relevance_rate <= 0.4:
        return -1.0

    return 0.0


def build_ml_feature_row(
    program_row,
    prefs,
    favorite_profile,
    feedback_profile,
    rule_score,
    program_favorite_counts=None,
    program_positive_feedback_counts=None,
):
    tuition = get_best_tuition(program_row)
    budget_min = safe_float(prefs.get("budget_min"), 0.0)
    budget_max = safe_float(prefs.get("budget_max"), 999999.0)

    program_location = normalize_location(program_row.get("Country", ""))
    program_mode = normalize_text(program_row.get("Study Mode", ""))
    program_intensity = normalize_text(program_row.get("Course Intensity", ""))
    program_broad = normalize_category(program_row.get("broad_category", program_row.get("Program Category", "")))
    program_detailed = normalize_detailed_category(program_row.get("detailed_category", ""))

    student_locations = prefs.get("locations", [])
    student_modes = prefs.get("study_modes", [])
    student_intensities = prefs.get("intensities", [])
    student_broad = prefs.get("categories", [])
    student_detailed = prefs.get("detailed_categories", [])

    program_tokens = tokenize_program_name(program_row.get("Program Name", ""))
    favorite_tokens = favorite_profile.get("name_tokens", set()) if favorite_profile else set()
    positive_tokens = feedback_profile.get("name_tokens_positive", set()) if feedback_profile else set()
    negative_tokens = feedback_profile.get("name_tokens_negative", set()) if feedback_profile else set()

    within_budget = 1 if tuition is None or tuition <= budget_max else 0
    tuition_value = float(tuition) if tuition is not None else 0.0
    tuition_missing = 1 if tuition is None else 0

    level_match = 1 if normalize_level(program_row.get("Study Level", "")) == prefs.get("level") else 0
    location_match = 1 if program_location in student_locations else 0
    mode_match = 1 if program_mode in student_modes else 0
    intensity_match = 1 if program_intensity in student_intensities else 0
    broad_category_match = 1 if program_matches_broad_interest(program_row, prefs) else 0
    detailed_category_match = 1 if program_detailed in student_detailed else 0
    rank_value = get_rank_value(program_row)
    rank_score = get_rank_score(program_row)
    rank_available = 1 if rank_value > 0 else 0
    top_50_uni = 1 if rank_available and rank_value <= 50 else 0
    top_100_uni = 1 if rank_available and rank_value <= 100 else 0
    top_300_uni = 1 if rank_available and rank_value <= 300 else 0

    req_ielts = safe_float(program_row.get("IELTS"), None)
    req_toefl = safe_float(program_row.get("TOEFL"), None)
    req_sat = safe_float(program_row.get("SAT"), None)
    req_gpa = safe_float(program_row.get("GPA"), None)

    student_ielts = safe_float(prefs.get("ielts"), None)
    student_toefl = safe_float(prefs.get("toefl"), None)
    student_sat = safe_float(prefs.get("sat"), None)
    student_gpa = safe_float(prefs.get("gpa"), None)

    has_any_exam_requirement = 1 if (req_ielts is not None or req_toefl is not None or req_sat is not None) else 0
    meets_any_exam = meets_any_exam_requirement(prefs, program_row)
    meets_ielts = meets_requirement(student_ielts, req_ielts)
    meets_toefl = meets_requirement(student_toefl, req_toefl)
    meets_sat = meets_requirement(student_sat, req_sat)
    meets_gpa = meets_requirement(student_gpa, req_gpa)

    program_key = (
        normalize_program_name(program_row.get("Program Name", "")),
        normalize_university_name(program_row.get("University Name", "")),
    )

    favorite_count_global = 0
    positive_feedback_count_global = 0

    if program_favorite_counts:
        favorite_count_global = int(program_favorite_counts.get(program_key, 0))
    if program_positive_feedback_counts:
        positive_feedback_count_global = int(program_positive_feedback_counts.get(program_key, 0))

    total_popularity = favorite_count_global + positive_feedback_count_global
    popularity_log = math.log1p(total_popularity)

    feature_row = {
        "feature_level_match": level_match,
        "feature_location_match": location_match,
        "feature_mode_match": mode_match,
        "feature_intensity_match": intensity_match,
        "feature_broad_category_match": broad_category_match,
        "feature_detailed_category_match": detailed_category_match,
        "feature_tuition_missing": tuition_missing,
        "feature_within_budget": within_budget,
        "feature_below_budget_min": 1 if tuition is not None and tuition < budget_min else 0,
        "feature_tuition_value": tuition_value,
        "feature_budget_distance_under": max(0.0, budget_min - tuition_value) if tuition is not None else 0.0,
        "feature_budget_distance_over": max(0.0, tuition_value - budget_max) if tuition is not None else 0.0,
        "feature_rank_available": rank_available,
        "feature_rank_value": rank_value,
        "feature_rank_score": rank_score,
        "feature_top_50_uni": top_50_uni,
        "feature_top_100_uni": top_100_uni,
        "feature_top_300_uni": top_300_uni,
        "feature_favorite_broad_overlap": 1 if favorite_profile and program_broad in favorite_profile.get("categories", set()) else 0,
        "feature_favorite_detailed_overlap": 1 if favorite_profile and program_detailed in favorite_profile.get("detailed_categories", set()) else 0,
        "feature_favorite_token_overlap": len(program_tokens & favorite_tokens),
        "feature_positive_feedback_token_overlap": len(program_tokens & positive_tokens),
        "feature_negative_feedback_token_overlap": len(program_tokens & negative_tokens),
        "feature_positive_feedback_category_signal": get_feedback_signal(
            feedback_profile.get("categories", {}) if feedback_profile else {},
            program_broad,
        ),
        "feature_positive_feedback_detailed_signal": get_feedback_signal(
            feedback_profile.get("detailed_categories", {}) if feedback_profile else {},
            program_detailed,
        ),
        "feature_positive_feedback_location_signal": get_feedback_signal(
            feedback_profile.get("locations", {}) if feedback_profile else {},
            program_location,
        ),
        "feature_positive_feedback_mode_signal": get_feedback_signal(
            feedback_profile.get("study_modes", {}) if feedback_profile else {},
            program_mode,
        ),
        "feature_positive_feedback_intensity_signal": get_feedback_signal(
            feedback_profile.get("intensities", {}) if feedback_profile else {},
            program_intensity,
        ),
        "feature_has_any_exam_requirement": has_any_exam_requirement,
        "feature_meets_any_exam_requirement": meets_any_exam,
        "feature_meets_ielts": meets_ielts,
        "feature_meets_toefl": meets_toefl,
        "feature_meets_sat": meets_sat,
        "feature_meets_gpa": meets_gpa,
        "feature_has_favorites": 1 if favorite_profile and favorite_profile.get("favorite_records") else 0,
        "feature_has_feedback": 1 if feedback_profile and feedback_profile.get("feedback_records") else 0,
        "student_budget_min": budget_min,
        "student_budget_max": budget_max,
        "student_locations_count": len(student_locations),
        "student_modes_count": len(student_modes),
        "student_intensities_count": len(student_intensities),
        "student_categories_count": len(student_broad),
        "student_detailed_categories_count": len(student_detailed),
        "feature_strong_pref_match": 1 if (level_match and location_match and broad_category_match) else 0,
        "feature_academic_pref_match": 1 if (level_match and (broad_category_match or detailed_category_match)) else 0,
        "feature_budget_rank_combo": float(within_budget) * float(rank_score),
        "feature_favorite_category_combo": (
            (1 if favorite_profile and program_broad in favorite_profile.get("categories", set()) else 0)
            * broad_category_match
        ),
        "feature_favorite_detailed_combo": (
            (1 if favorite_profile and program_detailed in favorite_profile.get("detailed_categories", set()) else 0)
            * detailed_category_match
        ),
        "feature_exam_and_level_combo": meets_any_exam_requirement(prefs, program_row) * level_match,
        "feature_location_category_combo": location_match * broad_category_match,
        "feature_program_favorite_count_global": favorite_count_global,
        "feature_program_positive_feedback_count_global": positive_feedback_count_global,
        "feature_program_total_popularity": total_popularity,
        "feature_popularity_log": popularity_log,
        "feature_rule_score_raw": float(rule_score),
    }

    return feature_row


def compute_ml_confidence(rule_item, ml_norm_score):
    confidence = 0.0

    if ml_norm_score >= 0.88:
        confidence += 0.50
    elif ml_norm_score >= 0.75:
        confidence += 0.35
    elif ml_norm_score >= 0.60:
        confidence += 0.20

    if rule_item.get("rule_score_norm", 0.0) >= 0.75:
        confidence += 0.20

    if rule_item.get("feature_strong_pref_match", 0) == 1:
        confidence += 0.12

    if rule_item.get("feature_within_budget", 0) == 1:
        confidence += 0.10

    if rule_item.get("feature_academic_pref_match", 0) == 1:
        confidence += 0.08

    return min(1.0, confidence)


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


def apply_hybrid_scores(
    rule_ranked_items,
    prefs,
    favorite_profile,
    feedback_profile,
    program_favorite_counts=None,
    program_positive_feedback_counts=None,
    hybrid_ml_weight=0.90,
):
    if not rule_ranked_items:
        return []

    model, feature_cols = get_cached_ranker()

    feature_rows = []
    for item in rule_ranked_items:
        row = build_ml_feature_row(
            item["_program_row"],
            prefs,
            favorite_profile,
            feedback_profile,
            item["rule_score"],
            program_favorite_counts=program_favorite_counts,
            program_positive_feedback_counts=program_positive_feedback_counts,
        )
        feature_rows.append(row)

    normalize_candidate_rule_scores(feature_rows)
    feature_df = pd.DataFrame(feature_rows)

    if model is not None and predict_ml_scores is not None:
        try:
            pred_df = predict_ml_scores(feature_df, model=model, feature_cols=feature_cols)
            ml_raw_scores = pred_df["ml_score"].tolist()
        except Exception:
            ml_raw_scores = [0.0] * len(rule_ranked_items)
    else:
        ml_raw_scores = [0.0] * len(rule_ranked_items)

    rule_raw_scores = [item["rule_score"] for item in rule_ranked_items]
    rule_norm_scores = minmax_normalize(rule_raw_scores)
    ml_norm_scores = minmax_normalize(ml_raw_scores)
    known_positive_keys = build_known_positive_program_keys(favorite_profile, feedback_profile)

    final_ranked = []
    for idx, item in enumerate(rule_ranked_items):
        item["rule_score_norm"] = rule_norm_scores[idx]
        item["feature_strong_pref_match"] = int(feature_rows[idx].get("feature_strong_pref_match", 0))
        item["feature_academic_pref_match"] = int(feature_rows[idx].get("feature_academic_pref_match", 0))
        item["feature_within_budget"] = int(feature_rows[idx].get("feature_within_budget", 0))

        ml_confidence = compute_ml_confidence(item, ml_norm_scores[idx])
        ml_weight, rule_weight, interaction_count = get_dynamic_hybrid_weights(
            favorite_profile,
            feedback_profile,
            ml_confidence=ml_confidence,
        )

        final_score = (ml_weight * ml_norm_scores[idx]) + (rule_weight * rule_norm_scores[idx])
        known_positive_boost = 0.30 if is_known_positive_program(
            item["program_name"],
            item["university"],
            known_positive_keys,
        ) else 0.0
        final_score += known_positive_boost

        cleaned_item = {
            "student_id": item["student_id"],
            "program_name": item["program_name"],
            "university": item["university"],
            "country": item["country"],
            "score": round(final_score, 4),
            "summary": item["summary"],
            "explanation": item["explanation"],
            "rule_score": round(item["rule_score"], 4),
            "ml_score": round(float(ml_raw_scores[idx]), 4),
            "rule_score_norm": round(rule_norm_scores[idx], 4),
            "ml_score_norm": round(ml_norm_scores[idx], 4),
            "ml_confidence": round(ml_confidence, 4),
            "ml_weight": round(ml_weight, 4),
            "rule_weight": round(rule_weight, 4),
            "interaction_count": interaction_count,
            "known_positive_boost": round(known_positive_boost, 4),
            "_program_row": item["_program_row"],  # keep full row for debugging/display
        }
        final_ranked.append(cleaned_item)

    final_ranked.sort(key=lambda x: x["score"], reverse=True)
    return final_ranked


def rank_candidates(
    candidates,
    prefs,
    favorite_profile=None,
    feedback_profile=None,
    min_score_threshold=None,
    program_favorite_counts=None,
    program_positive_feedback_counts=None,
    hybrid_ml_weight=0.90,
):
    rule_ranked = []

    for program in candidates:
        rule_score, explanation, summary = score_program(
            prefs,
            program,
            favorite_profile=favorite_profile,
            feedback_profile=feedback_profile,
        )

        if rule_score <= 0:
            continue

        if min_score_threshold is not None and rule_score < min_score_threshold:
            continue

        rule_ranked.append({
            "student_id": prefs["student_id"],
            "program_name": program.get("Program Name"),
            "university": program.get("University Name"),
            "country": program.get("Country"),
            "rule_score": rule_score,
            "summary": summary,
            "explanation": explanation,
            "_program_row": program,
        })

    if not rule_ranked:
        return []

    return apply_hybrid_scores(
        rule_ranked,
        prefs,
        favorite_profile=favorite_profile,
        feedback_profile=feedback_profile,
        program_favorite_counts=program_favorite_counts,
        program_positive_feedback_counts=program_positive_feedback_counts,
        hybrid_ml_weight=hybrid_ml_weight,
    )


def diversify_results(results, max_per_university=1, top_k=3):
    final_results = []
    university_counts = {}
    seen_programs = set()
    seen_families = set()

    for item in results:
        program_key = (item["program_name"], item["university"])
        if program_key in seen_programs:
            continue

        uni = item["university"]
        family = normalize_program_family(item["program_name"])

        if university_counts.get(uni, 0) >= max_per_university:
            continue

        if family in seen_families:
            continue

        final_results.append(item)
        seen_programs.add(program_key)
        seen_families.add(family)
        university_counts[uni] = university_counts.get(uni, 0) + 1

        if len(final_results) == top_k:
            return final_results

    for item in results:
        program_key = (item["program_name"], item["university"])
        if program_key in seen_programs:
            continue

        uni = item["university"]
        if university_counts.get(uni, 0) >= max_per_university:
            continue

        final_results.append(item)
        seen_programs.add(program_key)
        university_counts[uni] = university_counts.get(uni, 0) + 1

        if len(final_results) == top_k:
            break

    return final_results


def extend_unique_ranked(all_ranked, seen_programs, new_ranked):
    for item in new_ranked:
        key = (item["program_name"], item["university"])
        if key in seen_programs:
            continue
        all_ranked.append(item)
        seen_programs.add(key)


def recommend_top3(
    student_row,
    programs_df,
    favorites_df=None,
    feedback_df=None,
    max_per_university=None,
    exclude_seen=False,
    hybrid_ml_weight=None,
):
    prefs = extract_student_preferences(student_row)

    program_lookup = build_program_lookup(programs_df)
    program_favorite_counts, program_positive_feedback_counts = build_program_popularity_maps(
        favorites_df, feedback_df
    )

    favorite_profile = build_favorite_profile(
        prefs["student_id"], favorites_df, programs_df, program_lookup=program_lookup
    )
    feedback_profile = build_feedback_profile(
        prefs["student_id"], feedback_df, programs_df, program_lookup=program_lookup
    )

    all_ranked = []
    seen_programs = set()

    has_detailed = bool(prefs.get("detailed_categories", []))

    stage_thresholds = {
        1: 6.2,
        2: 5.8,
        3: 5.2,
        4: 4.8,
        5: 4.5,
    }

    stages = []

    if has_detailed:
        stages.extend([
            {
                "stage": 1,
                "use_detailed": True,
                "strict_location": True,
                "allow_near_category": False,
                "require_mode": False,
                "require_intensity": False,
            },
            {
                "stage": 2,
                "use_detailed": False,
                "strict_location": True,
                "allow_near_category": False,
                "require_mode": False,
                "require_intensity": False,
            },
            {
                "stage": 3,
                "use_detailed": True,
                "strict_location": False,
                "allow_near_category": False,
                "require_mode": True,
                "require_intensity": True,
            },
            {
                "stage": 4,
                "use_detailed": False,
                "strict_location": False,
                "allow_near_category": False,
                "require_mode": True,
                "require_intensity": True,
            },
            {
                "stage": 5,
                "use_detailed": False,
                "strict_location": False,
                "allow_near_category": True,
                "require_mode": False,
                "require_intensity": False,
            },
        ])
    else:
        stages.extend([
            {
                "stage": 1,
                "use_detailed": False,
                "strict_location": True,
                "allow_near_category": False,
                "require_mode": False,
                "require_intensity": False,
            },
            {
                "stage": 2,
                "use_detailed": False,
                "strict_location": False,
                "allow_near_category": False,
                "require_mode": True,
                "require_intensity": True,
            },
            {
                "stage": 3,
                "use_detailed": False,
                "strict_location": False,
                "allow_near_category": True,
                "require_mode": False,
                "require_intensity": False,
            },
        ])

    for stage_cfg in stages:
        candidates = filter_candidates(
            programs_df,
            prefs,
            strict_location=stage_cfg["strict_location"],
            hard_budget=True,
            use_detailed=stage_cfg["use_detailed"],
            allow_near_category=stage_cfg["allow_near_category"],
            require_mode=stage_cfg["require_mode"],
            require_intensity=stage_cfg["require_intensity"],
        )

        ranked = rank_candidates(
            candidates,
            prefs,
            favorite_profile=favorite_profile,
            feedback_profile=feedback_profile,
            min_score_threshold=stage_thresholds.get(stage_cfg["stage"], 4.5),
            program_favorite_counts=program_favorite_counts,
            program_positive_feedback_counts=program_positive_feedback_counts,
        )

        extend_unique_ranked(all_ranked, seen_programs, ranked)

        if len(all_ranked) >= 70:
            break

    all_ranked = sorted(all_ranked, key=lambda x: x["score"], reverse=True)

    if exclude_seen:
        seen_program_keys = build_seen_program_keys(
            favorites_df=favorites_df,
            feedback_df=feedback_df,
            student_id=prefs["student_id"],
        )
        all_ranked = remove_seen_programs(all_ranked, seen_program_keys)

    if max_per_university is None:
        return all_ranked[:3]

    return diversify_results(
        all_ranked,
        max_per_university=max_per_university,
        top_k=3,
    )