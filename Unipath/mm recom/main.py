from data_preprocessing import load_and_clean_data
from recommender import recommend_top3

PROGRAMS_PATH = "Programs_encoded.csv"
STUDENTS_PATH = "students_updated.csv"
FAVORITES_PATH = "favorites.csv"
FEEDBACK_PATH = "feedback.csv"

programs, students, favorites, feedback = load_and_clean_data(
    PROGRAMS_PATH,
    STUDENTS_PATH,
    FAVORITES_PATH,
    FEEDBACK_PATH,
)

for _, student in students.iterrows():
    print("\n" + "=" * 60)
    print(f"Student ID: {student['student_id']}")
    print(
        f"Preferences: level={student.get('academic_level', '')}, "
        f"location={student.get('preferred_location', '')}, "
        f"broad={student.get('broad_categories_interest', student.get('category', ''))}, "
        f"detailed={student.get('detailed_categories_interest', '')}, "
        f"mode={student.get('study_mode', '')}, "
        f"intensity={student.get('intensity', '')}, "
        f"budget={student.get('budget_min', '')}-{student.get('budget_max', '')}"
    )

    student_favorites = favorites[favorites["student_id"] == int(student["student_id"])]
    student_feedback = feedback[feedback["student_id"] == int(student["student_id"])]

    if not student_favorites.empty:
        fav_lines = []
        for _, fav in student_favorites.iterrows():
            pname = fav.get("program_name", "")
            uname = fav.get("uni_name", "")
            if str(uname).strip():
                fav_lines.append(f"{pname} - {uname}")
            else:
                fav_lines.append(str(pname))
        print("Favorites:", " | ".join(fav_lines))
    else:
        print("Favorites: None")

    if not student_feedback.empty:
        feedback_lines = []
        for _, fb in student_feedback.head(5).iterrows():
            label = "relevant" if int(fb.get("is_relevant", 0)) == 1 else "not relevant"
            feedback_lines.append(
                f"{fb.get('program_name', '')} - {fb.get('uni_name', '')} "
                f"(rating={fb.get('rating', '')}, {label})"
            )
        print("Feedback history:", " | ".join(feedback_lines))
    else:
        print("Feedback history: None")

    recommendations = recommend_top3(student, programs, favorites, feedback)

    if not recommendations:
        print("No suitable recommendations found.")
        continue

    for i, rec in enumerate(recommendations, start=1):
        print(f"\nTop {i}: {rec['program_name']} - {rec['university']} ({rec['country']})")
        print(f"Score: {rec['score']}")
        print(f"Why: {rec.get('summary', '')}")
        print("Match:", " | ".join(rec["explanation"]))