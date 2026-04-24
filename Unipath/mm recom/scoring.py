import math
import re


DETAILED_TO_BROAD = {
    "mechanical, manufacturing & industrial engineering": "engineering",
    "civil, structural & construction engineering": "engineering",
    "electrical, electronic & communications engineering": "engineering",
    "chemical, process & petroleum engineering": "engineering",
    "materials, mechatronics & general engineering": "engineering",
    "computer science, software & information systems": "computing & technology",
    "computer engineering, embedded systems & robotics": "computing & technology",
    "cybersecurity, networks & cloud computing": "computing & technology",
    "data science, artificial intelligence & machine learning": "data science & artificial intelligence",
    "business analytics, decision science & intelligent systems": "data science & artificial intelligence",
    "medicine, dentistry & clinical medicine": "health & medicine",
    "nursing, midwifery & patient care": "health & medicine",
    "allied health, physiotherapy & rehabilitation": "health & medicine",
    "pharmacy, pharmacology & pharmaceutical sciences": "health & medicine",
    "public health, nutrition & health management": "health & medicine",
    "biological sciences, genetics & molecular biology": "life sciences",
    "biotechnology, biomedical sciences & bioinformatics": "life sciences",
    "neuroscience, microbiology & life science research": "life sciences",
    "mathematics, statistics & actuarial science": "natural & physical sciences",
    "physics, astronomy & space science": "natural & physical sciences",
    "chemistry & chemical sciences": "natural & physical sciences",
    "earth sciences, geology & geophysics": "natural & physical sciences",
    "economics, econometrics & development studies": "social sciences",
    "political science, international relations & public affairs": "social sciences",
    "sociology, anthropology & human geography": "social sciences",
    "psychology, counseling & mental health": "psychology & behavioral sciences",
    "cognitive science & behavioral science": "psychology & behavioral sciences",
    "business administration, management & entrepreneurship": "business & management",
    "finance, accounting, banking & fintech": "business & management",
    "marketing, supply chain, logistics & operations": "business & management",
    "law, legal studies & justice": "law & governance",
    "governance, public policy & international law": "law & governance",
    "education, teaching & curriculum studies": "education",
    "educational leadership, special education & instruction": "education",
    "architecture, urban planning & built environment": "arts, design & architecture",
    "graphic, interior, industrial & fashion design": "arts, design & architecture",
    "fine arts, visual arts & creative practice": "arts, design & architecture",
    "music, performing arts & creative technology": "arts, design & architecture",
    "languages, linguistics, translation & applied languages": "humanities & languages",
    "literature, history, philosophy & religious studies": "humanities & languages",
    "cultural studies, area studies & heritage": "humanities & languages",
    "environmental science, sustainability & ecology": "environment & agriculture",
    "agriculture, forestry, food science & natural resources": "environment & agriculture",
    "media, communication, journalism & public relations": "media & communication",
    "film, digital media, advertising & content production": "media & communication",
    "hospitality, tourism, travel & hotel management": "hospitality & tourism",
    "sports science, exercise science & athletic performance": "sports & events",
    "event management, recreation & leisure studies": "sports & events",
    "aviation, aeronautics & aerospace transport": "transport, aviation & maritime",
    "maritime, shipping, logistics & transport systems": "transport, aviation & maritime",
    "interdisciplinary, innovation & emerging studies": "interdisciplinary & emerging fields",
}


def normalize_text(value: str) -> str:
    if value is None:
        return ""
    text = str(value).strip()
    if text.lower() in {"nan", "none", "null", "na", "n/a", "<na>"}:
        return ""
    return text.lower()


def normalize_level(value: str) -> str:
    value = normalize_text(value)
    mapping = {
        "bachelor": "bachelor",
        "bechalor": "bachelor",
        "undergraduate": "bachelor",
        "master": "master",
        "masters": "master",
        "postgraduate": "master",
    }
    return mapping.get(value, value)


def normalize_location(value: str) -> str:
    value = normalize_text(value)
    mapping = {
        "usa": "united states",
        "u.s.a": "united states",
    }
    return mapping.get(value, value)


def normalize_study_mode(value: str) -> str:
    value = normalize_text(value)
    mapping = {
        "on campus": "on campus",
        "oncampus": "on campus",
        "online": "online",
        "hybrid": "hybrid",
        "blended": "hybrid",
    }
    return mapping.get(value, value)


def normalize_intensity(value: str) -> str:
    value = normalize_text(value)
    mapping = {
        "full time": "full time",
        "full-time": "full time",
        "part time": "part time",
        "part-time": "part time",
    }
    return mapping.get(value, value)


def normalize_category(value: str) -> str:
    value = normalize_text(value)
    mapping = {
        "engineering": "engineering",
        "computing & technology": "computing & technology",
        "computing and technology": "computing & technology",
        "data science & artificial intelligence": "data science & artificial intelligence",
        "data science and artificial intelligence": "data science & artificial intelligence",
        "health & medicine": "health & medicine",
        "health and medicine": "health & medicine",
        "life sciences": "life sciences",
        "natural & physical sciences": "natural & physical sciences",
        "natural and physical sciences": "natural & physical sciences",
        "social sciences": "social sciences",
        "psychology & behavioral sciences": "psychology & behavioral sciences",
        "psychology and behavioral sciences": "psychology & behavioral sciences",
        "business & management": "business & management",
        "business and management": "business & management",
        "law & governance": "law & governance",
        "law and governance": "law & governance",
        "education": "education",
        "arts, design & architecture": "arts, design & architecture",
        "arts, design and architecture": "arts, design & architecture",
        "humanities & languages": "humanities & languages",
        "humanities and languages": "humanities & languages",
        "environment & agriculture": "environment & agriculture",
        "environment and agriculture": "environment & agriculture",
        "media & communication": "media & communication",
        "media and communication": "media & communication",
        "hospitality & tourism": "hospitality & tourism",
        "hospitality and tourism": "hospitality & tourism",
        "sports & events": "sports & events",
        "sports and events": "sports & events",
        "transport, aviation & maritime": "transport, aviation & maritime",
        "transport, aviation and maritime": "transport, aviation & maritime",
        "aviation & maritime": "transport, aviation & maritime",
        "aviation and maritime": "transport, aviation & maritime",
        "interdisciplinary & emerging fields": "interdisciplinary & emerging fields",
        "interdisciplinary and emerging fields": "interdisciplinary & emerging fields",
        "engineering & technology": "computing & technology",
        "engineering and technology": "computing & technology",
        "computer science": "computing & technology",
        "information technology": "computing & technology",
        "data science": "data science & artificial intelligence",
        "artificial intelligence": "data science & artificial intelligence",
        "life sciences & medicine": "health & medicine",
        "life sciences and medicine": "health & medicine",
        "medicine": "health & medicine",
        "medical": "health & medicine",
        "natural sciences": "natural & physical sciences",
        "physical sciences": "natural & physical sciences",
        "social sciences and management": "social sciences",
        "social science": "social sciences",
        "arts and humanities": "humanities & languages",
        "arts & humanities": "humanities & languages",
        "humanities": "humanities & languages",
        "law and legal studies": "law & governance",
        "legal studies": "law & governance",
        "environmental and agricultural sciences": "environment & agriculture",
        "media, communication and journalism": "media & communication",
        "hospitality, tourism and sports": "hospitality & tourism",
        "sports": "sports & events",
        "aviation and maritime studies": "transport, aviation & maritime",
    }
    return mapping.get(value, value)


def normalize_detailed_category(value: str) -> str:
    return normalize_text(value)


def tokenize_program_name(value: str):
    text = normalize_text(value)
    parts = re.findall(r"[a-zA-Z]+", text)
    stopwords = {
        "and", "of", "in", "for", "the", "with", "to", "on", "non", "thesis",
        "master", "bachelor", "science", "studies", "study", "program"
    }
    return {p for p in parts if len(p) > 2 and p not in stopwords}


def get_best_tuition(program_row):
    fee_columns = ["lebanese", "arab", "eu", "non eu", "us", "pal"]
    fees = []

    for col in fee_columns:
        value = program_row.get(col)
        if value is not None and not (isinstance(value, float) and math.isnan(value)):
            try:
                fees.append(float(value))
            except Exception:
                pass

    return min(fees) if fees else None


def get_program_rank(program_row):
    rank = program_row.get("Rank")
    try:
        rank = float(rank)
        if math.isnan(rank) or rank <= 0:
            return None
        return rank
    except Exception:
        return None


def is_strong_rank(program_row, threshold=100):
    rank = get_program_rank(program_row)
    return rank is not None and rank <= threshold


def is_budget_acceptable(student_prefs, tuition, hard_limit=True, program_row=None):
    if tuition is None:
        return True
    return tuition <= student_prefs["budget_max"]


def budget_score(student_prefs, tuition, program_row=None):
    if tuition is None:
        return 0.3, "Tuition not available"

    bmin = student_prefs["budget_min"]
    bmax = student_prefs["budget_max"]
    tuition_text = _fmt_money(tuition)

    if tuition > bmax:
        return 0.0, None

    if bmin <= tuition <= bmax:
        if program_row is not None and is_strong_rank(program_row):
            rank = int(get_program_rank(program_row))
            return 1.0, f"Fits your budget ({tuition_text}) and has a strong university rank ({rank})"
        return 1.0, f"Fits your budget ({tuition_text})"

    if tuition < bmin:
        if program_row is not None and is_strong_rank(program_row):
            rank = int(get_program_rank(program_row))
            return 0.85, f"Below your budget ({tuition_text}) and has a strong university rank ({rank})"
        return 0.6, f"Below your budget range ({tuition_text})"

    return 0.0, None


def admission_score(student_prefs, program_row):
    # Informational only. Does not affect score.
    return 0.0, []


def ranking_boost(program_row):
    rank = program_row.get("Rank")
    try:
        rank = float(rank)
        if rank > 0:
            return min(1.0, 120.0 / rank)
    except Exception:
        pass
    return 0.0


def get_program_keywords(program_row):
    name = normalize_text(program_row.get("Program Name", ""))
    category = normalize_category(
        program_row.get("broad_category", program_row.get("Program Category", ""))
    )
    detailed = normalize_detailed_category(program_row.get("detailed_category", ""))
    university = normalize_text(program_row.get("University Name", ""))

    text = f"{name} {category} {detailed} {university}"

    keyword_map = {
        "engineering": [
            "engineering", "civil", "mechanical", "electrical", "chemical",
            "industrial", "mechatronics", "robotics", "biomedical engineering"
        ],
        "computing & technology": [
            "computer science", "computer", "software", "informatics",
            "information systems", "cybersecurity", "cyber", "computing",
            "programming", "information technology", "computer engineering",
            "informatics engineering", "health informatics", "management information systems"
        ],
        "data science & artificial intelligence": [
            "data science", "data analytics", "data analysis", "artificial intelligence",
            "machine learning", "deep learning", "big data", "smart", "analytics"
        ],
        "health & medicine": [
            "medicine", "medical", "health", "public health", "nursing",
            "pharmacy", "clinical", "nutrition", "physiotherapy", "dentistry",
            "family medicine", "experimental medicine"
        ],
        "life sciences": [
            "biology", "biotechnology", "biochemistry", "genetics",
            "microbiology", "molecular", "life science", "animal biology"
        ],
        "natural & physical sciences": [
            "physics", "chemistry", "mathematics", "statistics",
            "natural science", "physical science", "astronomy"
        ],
        "social sciences": [
            "sociology", "politics", "international relations", "public policy",
            "social science", "economics", "global studies", "anthropology"
        ],
        "psychology & behavioral sciences": [
            "psychology", "behavioral", "behavioural", "cognitive science",
            "mental health", "neuroscience"
        ],
        "business & management": [
            "business", "management", "marketing", "finance",
            "accounting", "entrepreneurship", "administration", "supply chain",
            "management analytics"
        ],
        "law & governance": [
            "law", "legal", "governance", "public administration",
            "criminal law", "jurisprudence", "criminology"
        ],
        "education": [
            "education", "teaching", "teacher", "curriculum",
            "instructional", "pedagogy", "early childhood"
        ],
        "arts, design & architecture": [
            "design", "architecture", "visual arts", "graphic",
            "interior design", "urban design", "digital media",
            "fine arts", "dramatic arts", "composition", "creative",
            "musicology", "music", "creative technologies"
        ],
        "humanities & languages": [
            "history", "language", "literature", "philosophy",
            "translation", "religion", "linguistics", "spirituality",
            "archaeology", "ancient studies", "theology"
        ],
        "environment & agriculture": [
            "environment", "environmental", "agriculture", "agricultural",
            "sustainability", "ecology", "food science", "food technology"
        ],
        "media & communication": [
            "media", "communication", "journalism", "public relations",
            "film", "broadcast", "mass communication", "cinema", "audiovisual",
            "radio", "television"
        ],
        "hospitality & tourism": [
            "hospitality", "tourism", "hotel", "travel"
        ],
        "sports & events": [
            "sport", "sports", "event management", "athletic",
            "exercise science", "physical education"
        ],
        "transport, aviation & maritime": [
            "transport", "aviation", "maritime", "aerospace",
            "logistics", "shipping", "air transport", "road safety"
        ],
        "interdisciplinary & emerging fields": [
            "interdisciplinary", "emerging", "innovation", "smart systems",
            "digital transformation", "sustainability studies"
        ],
    }

    detected = set()
    for label, words in keyword_map.items():
        if any(word in text for word in words):
            detected.add(label)

    return detected


def detailed_category_score(student_prefs, program_row):
    student_detailed = student_prefs.get("detailed_categories", [])
    if not student_detailed:
        return 0.0, None

    program_detailed_raw = program_row.get("detailed_category", "")
    program_detailed = normalize_detailed_category(program_detailed_raw)

    if not program_detailed:
        return 0.0, None

    if program_detailed in student_detailed:
        return 1.0, f"Matches your detailed interest ({program_detailed_raw})"

    return 0.0, None


def category_score_for_one_interest(student_cat, program_row):
    raw_program_cat = program_row.get(
        "broad_category",
        program_row.get("Program Category", "")
    )
    program_cat = normalize_category(raw_program_cat)
    detected = get_program_keywords(program_row)

    if not student_cat:
        return 0.0, None, False

    if student_cat == program_cat:
        return 1.0, f"Matches your broad interest ({raw_program_cat})", False

    close_matches = {
        "engineering": {"computing & technology", "data science & artificial intelligence", "transport, aviation & maritime"},
        "computing & technology": {"engineering", "data science & artificial intelligence"},
        "data science & artificial intelligence": {"computing & technology", "natural & physical sciences"},
        "health & medicine": {"life sciences", "psychology & behavioral sciences"},
        "life sciences": {"health & medicine", "natural & physical sciences"},
        "natural & physical sciences": {"life sciences", "data science & artificial intelligence", "engineering"},
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

    if student_cat in detected:
        return 0.92, f"Matches one of your broad interests ({student_cat})", False

    if program_cat in close_matches.get(student_cat, set()) or any(cat in close_matches.get(student_cat, set()) for cat in detected):
        return 0.55, f"Partially matches one of your broad interests ({raw_program_cat})", False

    return 0.0, None, False


def category_score(student_prefs, program_row):
    detailed_score, detailed_reason = detailed_category_score(student_prefs, program_row)
    if detailed_score > 0:
        return detailed_score, detailed_reason, "detailed"

    student_categories = student_prefs.get("categories", [])
    if not student_categories:
        return 0.0, None, "none"

    best_score = 0.0
    best_reason = None

    for student_cat in student_categories:
        score, reason, blocked = category_score_for_one_interest(student_cat, program_row)
        if score > best_score:
            best_score = score
            best_reason = reason

    if best_score > 0:
        return best_score, best_reason, "broad"

    return 0.0, None, "none"


def favorite_similarity_reason(program_row, favorite_profile):
    if not favorite_profile:
        return None

    favorite_categories = favorite_profile.get("categories", set())
    favorite_name_tokens = favorite_profile.get("name_tokens", set())

    if not favorite_categories or not favorite_name_tokens:
        return None

    program_category = normalize_category(
        program_row.get("broad_category", program_row.get("Program Category", ""))
    )
    program_tokens = tokenize_program_name(program_row.get("Program Name", ""))

    if program_category not in favorite_categories:
        return None

    token_overlap = len(program_tokens & favorite_name_tokens)
    if token_overlap >= 1:
        return "Similar to your liked programs"

    return None


def feedback_preference_adjustment(program_row, feedback_profile):
    if not feedback_profile:
        return 0.0, []

    program_category = normalize_category(
        program_row.get("broad_category", program_row.get("Program Category", ""))
    )
    program_detailed = normalize_detailed_category(program_row.get("detailed_category", ""))
    program_location = normalize_location(program_row.get("Country", ""))
    program_mode = normalize_study_mode(program_row.get("Study Mode", ""))
    program_intensity = normalize_intensity(program_row.get("Course Intensity", ""))
    program_tokens = tokenize_program_name(program_row.get("Program Name", ""))

    score_delta = 0.0
    reasons = []

    def apply_signal(container, key, pos_reason, neg_reason, pos_weight, neg_weight):
        nonlocal score_delta
        if not key or key not in container:
            return

        stats = container[key]
        count = stats.get("count", 0)
        if count == 0:
            return

        avg_rating = stats["rating_sum"] / count
        relevance_rate = stats["relevant_sum"] / count

        if avg_rating >= 4.0 and relevance_rate >= 0.6:
            score_delta += pos_weight
            reasons.append(pos_reason)
        elif avg_rating <= 2.5 and relevance_rate <= 0.4:
            score_delta -= neg_weight
            reasons.append(neg_reason)

    apply_signal(
        feedback_profile.get("categories", {}),
        program_category,
        "Consistent with your past positive feedback",
        "Similar categories were rated poorly before",
        0.7,
        0.7,
    )
    apply_signal(
        feedback_profile.get("detailed_categories", {}),
        program_detailed,
        "Consistent with your past positive feedback",
        "Similar detailed interests received weak feedback before",
        0.5,
        0.5,
    )
    apply_signal(
        feedback_profile.get("locations", {}),
        program_location,
        "Consistent with your past positive feedback",
        "Similar locations received weak feedback before",
        0.4,
        0.4,
    )
    apply_signal(
        feedback_profile.get("study_modes", {}),
        program_mode,
        "Consistent with your past positive feedback",
        "Similar study modes received weak feedback before",
        0.2,
        0.2,
    )
    apply_signal(
        feedback_profile.get("intensities", {}),
        program_intensity,
        "Consistent with your past positive feedback",
        "Similar course intensities received weak feedback before",
        0.15,
        0.15,
    )

    positive_tokens = feedback_profile.get("name_tokens_positive", set())
    negative_tokens = feedback_profile.get("name_tokens_negative", set())

    if program_tokens & positive_tokens:
        score_delta += 0.25
        reasons.append("Consistent with your past positive feedback")

    if program_tokens & negative_tokens:
        score_delta -= 0.25
        reasons.append("Program title is similar to recommendations you disliked before")

    return score_delta, reasons


def _safe_float(value):
    try:
        if value is None:
            return None
        value = float(value)
        if math.isnan(value):
            return None
        return value
    except Exception:
        return None


def _fmt_num(value):
    value = _safe_float(value)
    if value is None:
        return ""
    return str(int(value)) if float(value).is_integer() else str(value)

def _fmt_money(value):
    value = _safe_float(value)
    if value is None:
        return "N/A"
    return str(int(value)) if float(value).is_integer() else f"{value:.2f}"

def _valid_requirement_scale(exam_name, value):
    value = _safe_float(value)
    if value is None:
        return False

    if exam_name == "IELTS":
        return 0 <= value <= 9
    if exam_name == "TOEFL":
        return 0 <= value <= 120
    if exam_name == "SAT":
        return 200 <= value <= 1600
    if exam_name == "GPA":
        return 0 <= value <= 4.5

    return False


def build_requirement_messages(student_prefs, program_row, max_messages=2):
    """
    Requirements are informational only.

    Updated logic:
    - Check IELTS / TOEFL / SAT together.
    - If the student satisfies ANY ONE of the required tests, do not show exam warnings for the others.
    - If none are satisfied:
        * if the student has one or more test scores, show the strongest failed exam warning
        * if the student has no test scores at all, show one missing exam requirement
    - GPA is independent.
    """
    prioritized = []

    req_ielts = _safe_float(program_row.get("IELTS"))
    req_toefl = _safe_float(program_row.get("TOEFL"))
    req_sat = _safe_float(program_row.get("SAT"))
    req_gpa = _safe_float(program_row.get("GPA"))

    student_ielts = _safe_float(student_prefs.get("ielts"))
    student_toefl = _safe_float(student_prefs.get("toefl"))
    student_sat = _safe_float(student_prefs.get("sat"))
    student_gpa = _safe_float(student_prefs.get("gpa"))

    exam_pairs = []

    if _valid_requirement_scale("IELTS", req_ielts):
        exam_pairs.append(("IELTS", student_ielts, req_ielts))
    if _valid_requirement_scale("TOEFL", req_toefl):
        exam_pairs.append(("TOEFL", student_toefl, req_toefl))
    if _valid_requirement_scale("SAT", req_sat):
        exam_pairs.append(("SAT", student_sat, req_sat))

    # Step 1: if student satisfies ANY one of the available exam requirements, stop exam messaging entirely
    exam_satisfied = False
    for exam_name, student_score, req_score in exam_pairs:
        if student_score is not None and student_score >= req_score:
            exam_satisfied = True
            break

    # Step 2: only if none satisfied, decide what to show
    if not exam_satisfied:
        failed_exam_messages = []
        missing_exam_messages = []

        for exam_name, student_score, req_score in exam_pairs:
            if student_score is None:
                missing_exam_messages.append((
                    2,
                    f"Requires {exam_name} {_fmt_num(req_score)} — you may need to take this exam"
                ))
            elif student_score < req_score:
                gap = req_score - student_score
                failed_exam_messages.append((
                    1,
                    gap,
                    f"Your {exam_name} score is below the requirement ({_fmt_num(student_score)} vs {_fmt_num(req_score)}) — you may need to retake it"
                ))

        # If student has at least one exam score but none passed, show only one failed warning:
        # the exam they are closest to passing.
        if failed_exam_messages:
            failed_exam_messages.sort(key=lambda x: x[1])
            prioritized.append((1, failed_exam_messages[0][2]))
        # If student has no exam scores at all, show one missing exam requirement.
        elif missing_exam_messages:
            prioritized.append(missing_exam_messages[0])

    # GPA handled independently
    if _valid_requirement_scale("GPA", req_gpa):
        if student_gpa is None:
            prioritized.append((
                2,
                f"Requires minimum GPA of {_fmt_num(req_gpa)} — make sure you meet this requirement"
            ))
        elif student_gpa < req_gpa:
            prioritized.append((
                1,
                f"Your GPA is below the requirement ({_fmt_num(student_gpa)} vs {_fmt_num(req_gpa)}) — you may need to improve it"
            ))

    prioritized.sort(key=lambda x: x[0])
    return [msg for _, msg in prioritized[:max_messages]]


def simplify_reason(reason):
    if reason.startswith("Matches your detailed interest"):
        return "Strong category fit"

    if reason.startswith("Matches your broad interest"):
        return "Strong category fit"

    if reason.startswith("Matches one of your broad interests"):
        return "Strong category fit"

    if reason.startswith("Partially matches one of your broad interests"):
        return "Related to your academic interests"

    if reason.startswith("Matches one of your preferred locations"):
        return "Aligns with your preferred country"

    if reason.startswith("Outside your preferred locations"):
        return "Outside your preferred country"

    if reason.startswith("Matches one of your preferred study modes"):
        return "Matches your preferred study mode"

    if reason.startswith("Partially matches your study mode"):
        return "Reasonable study mode match"

    if reason.startswith("Matches one of your preferred intensities"):
        return "Matches your preferred intensity"

    if reason.startswith("Fits your budget and has a strong university rank"):
        return "Suitable budget option from a highly ranked university"

    if reason.startswith("Fits your budget"):
        return "Suitable budget option"

    if reason.startswith("Below your budget and has a strong university rank"):
        return "Good value from a highly ranked university"

    if reason.startswith("Below your budget range"):
        return "Good value option"

    if reason.startswith("Good value option within your budget"):
        return "Good value option"

    if reason.startswith("Offered by a highly ranked university"):
        return "Highly ranked university"

    if reason.startswith("Similar to your liked programs"):
        return "Similar to your liked programs"

    if reason.startswith("Consistent with your past positive feedback"):
        return "Consistent with your past positive feedback"

    if reason.startswith("Program title is similar to recommendations you disliked before"):
        return "May be less aligned with your past feedback"

    if reason.startswith("Similar categories were rated poorly before"):
        return "May be less aligned with your past feedback"

    if reason.startswith("Similar detailed interests received weak feedback before"):
        return "May be less aligned with your past feedback"

    if reason.startswith("Similar locations received weak feedback before"):
        return "May be less aligned with your past feedback"

    if reason.startswith("Similar study modes received weak feedback before"):
        return "May be less aligned with your past feedback"

    if reason.startswith("Similar course intensities received weak feedback before"):
        return "May be less aligned with your past feedback"

    return reason


def build_reason_groups(reasons):
    groups = {
        "category": [],
        "location": [],
        "mode": [],
        "intensity": [],
        "budget": [],
        "rank": [],
        "favorites": [],
        "feedback_positive": [],
        "feedback_negative": [],
        "other": [],
    }

    for reason in reasons:
        if reason.startswith((
            "Matches your detailed interest",
            "Matches your broad interest",
            "Matches one of your broad interests",
            "Partially matches one of your broad interests",
            "Recommended based on your broad interest",
        )):
            groups["category"].append(reason)
        elif reason.startswith(("Matches one of your preferred locations", "Outside your preferred locations")):
            groups["location"].append(reason)
        elif reason.startswith(("Matches one of your preferred study modes", "Partially matches your study mode")):
            groups["mode"].append(reason)
        elif reason.startswith("Matches one of your preferred intensities"):
            groups["intensity"].append(reason)
        elif reason.startswith(("Fits your budget", "Below your budget", "Good value option within your budget")):
            groups["budget"].append(reason)
        elif reason.startswith("Offered by a highly ranked university"):
            groups["rank"].append(reason)
        elif reason.startswith("Similar to your liked programs"):
            groups["favorites"].append(reason)
        elif reason.startswith("Consistent with your past positive feedback"):
            groups["feedback_positive"].append(reason)
        elif reason.startswith((
            "Similar categories were rated poorly before",
            "Similar detailed interests received weak feedback before",
            "Similar locations received weak feedback before",
            "Similar study modes received weak feedback before",
            "Similar course intensities received weak feedback before",
            "Program title is similar to recommendations you disliked before",
        )):
            groups["feedback_negative"].append(reason)
        else:
            groups["other"].append(reason)

    return groups


def select_diverse_reasons(reasons, max_reasons=4):
    groups = build_reason_groups(reasons)

    selected = []
    preferred_order = [
        "category",
        "location",
        "budget",
        "favorites",
        "feedback_positive",
        "rank",
        "mode",
        "intensity",
    ]

    for group_name in preferred_order:
        if groups[group_name]:
            selected.append(groups[group_name][0])

    if not selected and groups["other"]:
        selected.append(groups["other"][0])

    if len(selected) < max_reasons and groups["feedback_negative"] and len(selected) <= 2:
        selected.append(groups["feedback_negative"][0])

    fallback_order = [
        "category",
        "location",
        "budget",
        "favorites",
        "feedback_positive",
        "rank",
        "mode",
        "intensity",
        "other",
    ]

    seen = set(selected)
    for group_name in fallback_order:
        for reason in groups[group_name]:
            if reason not in seen:
                selected.append(reason)
                seen.add(reason)
                if len(selected) >= max_reasons:
                    break
        if len(selected) >= max_reasons:
            break

    simplified = []
    seen_simple = set()

    for reason in selected:
        simple = simplify_reason(reason)

        if simple == "Highly ranked university" and (
            "Suitable budget option from a highly ranked university" in seen_simple
            or "Good value from a highly ranked university" in seen_simple
        ):
            continue

        if simple not in seen_simple:
            simplified.append(simple)
            seen_simple.add(simple)

        if len(simplified) >= max_reasons:
            break

    return simplified


def generate_summary(student_prefs, program_row, reasons):
    program_name = str(program_row.get("Program Name", "")).strip()
    country = str(program_row.get("Country", "")).strip()

    has_strong_fit = "Strong category fit" in reasons
    has_location = "Aligns with your preferred country" in reasons
    has_budget = any(r in reasons for r in [
        "Suitable budget option",
        "Suitable budget option from a highly ranked university",
        "Good value option",
        "Good value from a highly ranked university",
    ])
    has_feedback = "Consistent with your past positive feedback" in reasons
    has_favorites = "Similar to your liked programs" in reasons
    has_rank = any(r in reasons for r in [
        "Highly ranked university",
        "Suitable budget option from a highly ranked university",
        "Good value from a highly ranked university",
    ])

    if has_strong_fit and has_location:
        return f"{program_name} is a strong fit because it aligns well with your academic interests and preferred country in {country}."

    if has_strong_fit and has_feedback:
        return f"{program_name} is a strong fit because it matches your interests and is consistent with your past positive feedback."

    if has_strong_fit and has_budget:
        return f"{program_name} is a strong fit because it matches your interests and remains a suitable budget option."

    if has_favorites:
        return f"{program_name} is recommended because it is similar to programs you liked before."

    if has_feedback:
        return f"{program_name} is recommended because it is consistent with your past positive feedback."

    if has_budget and has_rank:
        return f"{program_name} stands out as a cost-effective option from a highly ranked university."

    if has_strong_fit:
        return f"{program_name} is a strong match for your academic interests."

    if has_budget:
        return f"{program_name} is a practical option that fits your budget well."

    return f"{program_name} is one of the closest available matches based on your preferences."


def score_program(student_prefs, program_row, favorite_profile=None, feedback_profile=None):
    score = 0.0
    reasons = []

    program_level = normalize_level(program_row.get("Study Level", ""))
    program_location = normalize_location(program_row.get("Country", ""))
    program_mode = normalize_study_mode(program_row.get("Study Mode", ""))
    program_intensity = normalize_intensity(program_row.get("Course Intensity", ""))
    tuition = get_best_tuition(program_row)

    student_locations = student_prefs.get("locations", [])
    student_modes = student_prefs.get("study_modes", [])
    student_intensities = student_prefs.get("intensities", [])

    category_match, category_reason, _ = category_score(student_prefs, program_row)

    if program_level == student_prefs["level"]:
        score += 1.2
        reasons.append(f"Program level: {program_level.title()} (matches your target level)")
    else:
        score -= 2.5
        reasons.append(f"Program level: {program_level.title()}")

    if program_location in student_locations:
        score += 3.2
        reasons.append(f"Matches one of your preferred locations ({program_row.get('Country')})")
    else:
        score -= 1.0
        reasons.append(f"Outside your preferred locations ({program_row.get('Country')})")

    if program_mode in student_modes:
        score += 1.6
        reasons.append(f"Matches one of your preferred study modes ({program_row.get('Study Mode')})")
    elif "hybrid" in student_modes and program_mode in {"on campus", "online"}:
        score += 0.5
        reasons.append(f"Partially matches your study mode ({program_row.get('Study Mode')})")
    else:
        score -= 0.4

    if program_intensity in student_intensities:
        score += 1.0
        reasons.append(f"Matches one of your preferred intensities ({program_row.get('Course Intensity')})")
    else:
        score -= 0.3

    score += 4.0 * category_match
    if category_reason:
        reasons.append(category_reason)
    else:
        score -= 2.0

    b_score, b_reason = budget_score(student_prefs, tuition, program_row)
    if b_score == 0.0 and b_reason is None and tuition is not None and tuition > student_prefs["budget_max"]:
        return -1.0, [], ""

    score += 2.3 * b_score
    if b_reason:
        reasons.append(b_reason)

    score += 0.5 * ranking_boost(program_row)

    liked_reason = favorite_similarity_reason(program_row, favorite_profile)
    if liked_reason:
        reasons.append(liked_reason)

    feedback_delta, feedback_reasons = feedback_preference_adjustment(program_row, feedback_profile)
    score += feedback_delta
    reasons.extend(feedback_reasons)

    if tuition is not None and tuition < student_prefs["budget_min"]:
        reasons.append("Good value option within your budget")

    if is_strong_rank(program_row):
        reasons.append("Offered by a highly ranked university")

    if not student_prefs.get("detailed_categories"):
        reasons.append("Recommended based on your broad interest")

    unique_reasons = []
    seen = set()
    for reason in reasons:
        if reason not in seen:
            unique_reasons.append(reason)
            seen.add(reason)

    core_reasons = select_diverse_reasons(unique_reasons, max_reasons=4)
    requirement_messages = build_requirement_messages(student_prefs, program_row, max_messages=2)

    final_reasons = core_reasons + requirement_messages
    summary = generate_summary(student_prefs, program_row, core_reasons)

    return round(score, 4), final_reasons, summary