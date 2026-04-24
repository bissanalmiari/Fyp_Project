def normalize_text(value: str) -> str:
    if value is None:
        return ""
    text = str(value).strip()
    if text.lower() in {"nan", "none", "null", "na", "n/a", "<na>"}:
        return ""
    return text.lower()


def normalize_program_name(value: str) -> str:
    return normalize_text(value)


def normalize_level(value: str) -> str:
    value = normalize_text(value)
    mapping = {
        "high_school": "bachelor",
        "high school": "bachelor",
        "highschool": "bachelor",
        "school": "bachelor",
        "undergraduate": "master",
        "bachelor": "master",
        "bechalor": "master",
        "undergrad": "master",
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
        "oncampus": "on campus",
        "on campus": "on campus",
        "online": "online",
        "hybrid": "hybrid",
        "blended": "hybrid",
    }
    return mapping.get(value, value)


def normalize_intensity(value: str) -> str:
    value = normalize_text(value)
    mapping = {
        "full-time": "full time",
        "full time": "full time",
        "part-time": "part time",
        "part time": "part time",
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


KNOWN_BROAD_CATEGORIES = [
    "engineering",
    "computing & technology",
    "data science & artificial intelligence",
    "health & medicine",
    "life sciences",
    "natural & physical sciences",
    "social sciences",
    "psychology & behavioral sciences",
    "business & management",
    "law & governance",
    "education",
    "arts, design & architecture",
    "humanities & languages",
    "environment & agriculture",
    "media & communication",
    "hospitality & tourism",
    "sports & events",
    "transport, aviation & maritime",
    "interdisciplinary & emerging fields",
]


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


def normalize_detailed_category(value: str) -> str:
    return normalize_text(value)


def parse_multi_values(value, normalizer, separators=None):
    if value is None:
        return []

    text = str(value).strip()
    if not text:
        return []

    if separators is None:
        separators = [",", ";", "|", "/"]

    parts = [text]
    for sep in separators:
        temp = []
        for item in parts:
            temp.extend(item.split(sep))
        parts = temp

    cleaned = []
    seen = set()

    for part in parts:
        norm = normalizer(part)
        if norm and norm not in seen:
            cleaned.append(norm)
            seen.add(norm)

    return cleaned


def parse_category_values(value):
    values = parse_multi_values(
        value,
        normalize_category,
        separators=[";", "|"],
    )

    expanded = []
    seen = set()

    for item in values:
        matches = [category for category in KNOWN_BROAD_CATEGORIES if category in item]

        if len(matches) > 1:
            next_values = matches
        else:
            next_values = [item]

        for next_value in next_values:
            if next_value and next_value not in seen:
                expanded.append(next_value)
                seen.add(next_value)

    return expanded


def parse_detailed_categories(value):
    return parse_multi_values(value, normalize_detailed_category, separators=[";", "|"])


def build_broad_categories_from_detailed(detailed_categories):
    broad = []
    seen = set()
    for item in detailed_categories:
        mapped = DETAILED_TO_BROAD.get(item)
        if mapped and mapped not in seen:
            broad.append(mapped)
            seen.add(mapped)
    return broad


def get_first_available(student_row, keys, default=""):
    for key in keys:
        if key in student_row:
            value = student_row.get(key, default)
            if normalize_text(value) != "":
                return value
    return default


def extract_student_preferences(student_row):
    locations = parse_multi_values(get_first_available(student_row, ["preferred_location", "preferred_locations"]), normalize_location)
    study_modes = parse_multi_values(get_first_available(student_row, ["study_mode", "study_modes"]), normalize_study_mode)
    intensities = parse_multi_values(get_first_available(student_row, ["intensity", "intensities"]), normalize_intensity)

    broad_from_category = parse_category_values(
        get_first_available(student_row, ["category"]),
    )
    broad_from_interest = parse_category_values(
        get_first_available(student_row, ["broad_categories_interest", "broad_category", "broad_interests"]),
    )

    detailed_categories = parse_detailed_categories(
        get_first_available(student_row, ["detailed_categories_interest", "detailed_category", "detailed_interests"])
    )

    broad_from_detailed = build_broad_categories_from_detailed(detailed_categories)

    categories = []
    seen = set()
    for source in [broad_from_category, broad_from_interest, broad_from_detailed]:
        for cat in source:
            if cat and cat not in seen:
                categories.append(cat)
                seen.add(cat)

    return {
        "student_id": student_row.get("student_id"),
        "level": normalize_level(get_first_available(student_row, ["academic_level", "study_level", "level"])),
        "locations": locations,
        "study_modes": study_modes,
        "intensities": intensities,
        "categories": categories,
        "detailed_categories": detailed_categories,
        "budget_min": float(student_row.get("budget_min", 0) or 0),
        "budget_max": float(student_row.get("budget_max", 999999) or 999999),
        "language": normalize_text(student_row.get("language", "")),
        "ielts": student_row.get("ielts"),
        "toefl": student_row.get("toefl"),
        "sat": student_row.get("sat"),
        "gpa": student_row.get("gpa"),
    }
