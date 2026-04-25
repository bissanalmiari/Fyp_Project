import pandas as pd


def safe_read_csv(path):
    for enc in ["utf-8", "cp1252", "latin1"]:
        try:
            return pd.read_csv(path, encoding=enc)
        except Exception:
            continue
    raise ValueError(f"Could not read CSV file: {path}")


def clean_text(value):
    if pd.isna(value):
        return ""
    return str(value).strip()


def load_favorites_data(favorites_path):
    """
    Load favorites CSV safely.

    Supported schema:
    - student_id
    - program_name
    - uni_name

    If uni_name is missing, it will be created as empty text
    so old files still work.
    """
    try:
        favorites = safe_read_csv(favorites_path)
    except Exception:
        return pd.DataFrame(columns=["student_id", "program_name", "uni_name"])

    favorites = favorites.loc[:, ~favorites.columns.str.contains(r"^Unnamed")]
    favorites.columns = [clean_text(c) for c in favorites.columns]

    for col in favorites.select_dtypes(include="object").columns:
        favorites[col] = favorites[col].astype(str).str.strip()

    if "student_id" not in favorites.columns:
        favorites["student_id"] = pd.NA

    if "program_name" not in favorites.columns:
        favorites["program_name"] = ""

    if "uni_name" not in favorites.columns:
        favorites["uni_name"] = ""

    favorites["student_id"] = pd.to_numeric(favorites["student_id"], errors="coerce")
    favorites["program_name"] = favorites["program_name"].astype(str).str.strip()
    favorites["uni_name"] = favorites["uni_name"].astype(str).str.strip()

    favorites = favorites.dropna(subset=["student_id"]).copy()
    favorites = favorites[favorites["program_name"] != ""].copy()
    favorites["student_id"] = favorites["student_id"].astype(int)

    favorites = favorites.drop_duplicates(
        subset=["student_id", "program_name", "uni_name"]
    ).copy()

    return favorites


def load_feedback_data(feedback_path):
    """
    Load recommendation feedback CSV safely.

    Current CSV testing schema:
    - student_id
    - program_name
    - uni_name
    - rating          (expected 1 to 5)
    - is_relevant     (1/0, true/false, yes/no)
    """
    try:
        feedback = safe_read_csv(feedback_path)
    except Exception:
        return pd.DataFrame(columns=["student_id", "program_name", "uni_name", "rating", "is_relevant"])

    feedback = feedback.loc[:, ~feedback.columns.str.contains(r"^Unnamed")]
    feedback.columns = [clean_text(c) for c in feedback.columns]

    for col in feedback.select_dtypes(include="object").columns:
        feedback[col] = feedback[col].astype(str).str.strip()

    defaults = {
        "student_id": pd.NA,
        "program_name": "",
        "uni_name": "",
        "rating": pd.NA,
        "is_relevant": pd.NA,
    }
    for col, default_value in defaults.items():
        if col not in feedback.columns:
            feedback[col] = default_value

    feedback["student_id"] = pd.to_numeric(feedback["student_id"], errors="coerce")
    feedback["rating"] = pd.to_numeric(feedback["rating"], errors="coerce")

    def normalize_relevance(value):
        text = clean_text(value).lower()
        if text in {"1", "true", "yes", "y"}:
            return 1
        if text in {"0", "false", "no", "n"}:
            return 0
        try:
            return 1 if float(text) >= 1 else 0
        except Exception:
            return pd.NA

    feedback["is_relevant"] = feedback["is_relevant"].apply(normalize_relevance)
    feedback["program_name"] = feedback["program_name"].astype(str).str.strip()
    feedback["uni_name"] = feedback["uni_name"].astype(str).str.strip()

    feedback = feedback.dropna(subset=["student_id"]).copy()
    feedback = feedback[feedback["program_name"] != ""].copy()
    feedback["student_id"] = feedback["student_id"].astype(int)

    feedback["rating"] = feedback["rating"].fillna(3).clip(lower=1, upper=5)
    feedback["is_relevant"] = feedback["is_relevant"].fillna(1).astype(int)

    feedback = feedback.drop_duplicates(
        subset=["student_id", "program_name", "uni_name", "rating", "is_relevant"]
    ).copy()

    return feedback


def load_and_clean_data(programs_path, students_path, favorites_path=None, feedback_path=None):
    programs = safe_read_csv(programs_path)
    students = safe_read_csv(students_path)

    programs = programs.loc[:, ~programs.columns.str.contains(r"^Unnamed")]
    students = students.loc[:, ~students.columns.str.contains(r"^Unnamed")]

    programs.columns = [clean_text(c) for c in programs.columns]
    students.columns = [clean_text(c) for c in students.columns]

    programs = programs.drop_duplicates(subset=["University Name", "Program Name"]).copy()

    for col in programs.select_dtypes(include="object").columns:
        programs[col] = programs[col].astype(str).str.strip()

    for col in students.select_dtypes(include="object").columns:
        students[col] = students[col].astype(str).str.strip()

    numeric_program_cols = [
        "Rank", "eu", "non eu", "arab", "lebanese", "pal", "us",
        "TOEFL", "IELTS", "SAT", "GPA"
    ]
    for col in numeric_program_cols:
        if col in programs.columns:
            programs[col] = pd.to_numeric(programs[col], errors="coerce")

    numeric_student_cols = ["budget_min", "budget_max", "ielts", "toefl", "sat", "gpa"]
    for col in numeric_student_cols:
        if col in students.columns:
            students[col] = pd.to_numeric(students[col], errors="coerce")

    tuition_cols = ["eu", "non eu", "arab", "lebanese", "pal", "us"]

    # Keep 0 as valid tuition because some countries/universities are free
    for col in tuition_cols:
        if col in programs.columns:
            programs[col] = programs[col].apply(
                lambda x: x if pd.notna(x) and x >= 0 else pd.NA
            )

    existing_tuition_cols = [col for col in tuition_cols if col in programs.columns]
    if existing_tuition_cols:
        programs = programs.dropna(subset=existing_tuition_cols, how="all").copy()

    favorites = load_favorites_data(favorites_path) if favorites_path else pd.DataFrame(
        columns=["student_id", "program_name", "uni_name"]
    )

    feedback = load_feedback_data(feedback_path) if feedback_path else pd.DataFrame(
        columns=["student_id", "program_name", "uni_name", "rating", "is_relevant"]
    )

    return programs, students, favorites, feedback