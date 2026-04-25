import json
import os
import sys

from data_preprocessing import load_and_clean_data
from recommender import recommend_top3


def main():
    if len(sys.argv) < 2:
        raise SystemExit("Usage: python recommend_for_student.py <student-json-path>")

    script_dir = os.path.dirname(os.path.abspath(__file__))
    os.chdir(script_dir)

    with open(sys.argv[1], "r", encoding="utf-8-sig") as handle:
        student_row = json.load(handle)

    favorites_path = sys.argv[2] if len(sys.argv) > 2 else None
    feedback_path = sys.argv[3] if len(sys.argv) > 3 else None

    programs, _, favorites, feedback = load_and_clean_data(
        os.path.join(script_dir, "Programs_encoded.csv"),
        os.path.join(script_dir, "students_updated.csv"),
        favorites_path,
        feedback_path,
    )

    recommendations = recommend_top3(student_row, programs, favorites, feedback)

    cleaned = []
    for index, item in enumerate(recommendations[:3], start=1):
        explanation = item.get("explanation", [])
        if not isinstance(explanation, list):
            explanation = [str(explanation)]

        cleaned.append({
            "rank": index,
            "program_name": item.get("program_name", ""),
            "university": item.get("university", ""),
            "country": item.get("country", ""),
            "score": item.get("score", 0),
            "summary": item.get("summary", ""),
            "explanation": explanation,
        })

    print(json.dumps(cleaned, ensure_ascii=True))


if __name__ == "__main__":
    main()
