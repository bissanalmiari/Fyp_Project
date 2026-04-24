
import pandas as pd

from data_preprocessing import load_and_clean_data
from recommender import recommend_top3

PROGRAMS_PATH = "Programs_encoded.csv"
STUDENTS_PATH = "students_updated.csv"
FAVORITES_PATH = "favorites.csv"
FEEDBACK_PATH = "feedback.csv"


def build_test_student():
    student_data = {
        "student_id": 888888,

        "academic_level": "high_schooler",

        "preferred_location": "lebanon|italy",

        "broad_categories_interest": "computing & technology|data science & artificial intelligence",

        "detailed_categories_interest": (
            "computer science, software & information systems|"
            "data science, artificial intelligence & machine learning"
        ),

        "study_mode": "on campus",
        "intensity": "full time",

        "budget_min": 2000,
        "budget_max": 20000,

        "ielts": 6.0,
        "toefl": None,
        "sat": None,
        "gpa": 3.0,

        "language": "english",
    }

    return pd.Series(student_data)

def print_student_preferences(student):
    print("\n" + "=" * 60)
    print("CUSTOM TEST STUDENT")
    print("=" * 60)
    print(
        f"Preferences: level={student.get('academic_level', '')}, "
        f"location={student.get('preferred_location', '')}, "
        f"broad={student.get('broad_categories_interest', student.get('category', ''))}, "
        f"detailed={student.get('detailed_categories_interest', '')}, "
        f"mode={student.get('study_mode', '')}, "
        f"intensity={student.get('intensity', '')}, "
        f"budget={student.get('budget_min', '')}-{student.get('budget_max', '')}"
    )
    print(
        f"IELTS={student.get('ielts', None)}, "
        f"TOEFL={student.get('toefl', None)}, "
        f"SAT={student.get('sat', None)}, "
        f"GPA={student.get('gpa', None)}"
    )


def main():
    programs, students, favorites, feedback = load_and_clean_data(
        PROGRAMS_PATH,
        STUDENTS_PATH,
        FAVORITES_PATH,
        FEEDBACK_PATH,
    )

    student = build_test_student()
    print_student_preferences(student)

    # For a truly new student, these are empty on purpose.
    # ---------- FAVORITES ----------
    custom_favorites = pd.DataFrame([
        {
            "student_id": 888888,
            "program_name": "Computer Science",
            "uni_name": "Jinan University"
        },
        {
            "student_id": 888888,
            "program_name": "Data Science",
            "uni_name": "University of Bologna"
        }
    ])

    # ---------- FEEDBACK ----------
    custom_feedback = pd.DataFrame([
        {
            "student_id": 888888,
            "program_name": "Computer Science",
            "uni_name": "Rafik Hariri University",
            "rating": 5,
            "is_relevant": 1
        },
        {
            "student_id": 888888,
            "program_name": "Business Administration",
            "uni_name": "Some University",
            "rating": 2,
            "is_relevant": 0
        },
        {
            "student_id": 888888,
            "program_name": "Cybersecurity",
            "uni_name": "Another University",
            "rating": 4,
            "is_relevant": 1
        }
    ])

    recommendations = recommend_top3(
        student_row=student,
        programs_df=programs,
        favorites_df=custom_favorites,
        feedback_df=custom_feedback,
    )

    if not recommendations:
        print("\nNo suitable recommendations found.")
        return

    print("\n" + "=" * 60)
    print("TOP RECOMMENDATIONS")
    print("=" * 60)

    for i, rec in enumerate(recommendations, start=1):
        program_row = rec.get("_program_row", {})

        print(f"\nTop {i}: {rec['program_name']} - {rec['university']} ({rec['country']})")
        print(f"Level: {program_row.get('Study Level', 'Unknown')}")
        print(f"Score: {rec['score']}")
        print(f"Why: {rec.get('summary', '')}")
        print("Match:", " | ".join(rec["explanation"]))


if __name__ == "__main__":
    main()
