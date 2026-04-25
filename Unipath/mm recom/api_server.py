import json
import os
import tempfile
from http.server import BaseHTTPRequestHandler, HTTPServer

from data_preprocessing import load_and_clean_data
from recommend_for_student import get_program_field
from recommender import recommend_top3


def expected_api_key():
    return os.environ.get("RECOMMENDER_API_KEY", "").strip()


def authorized(headers):
    api_key = expected_api_key()
    if not api_key:
        return True

    return headers.get("X-API-Key", "").strip() == api_key


def build_recommendations(payload):
    script_dir = os.path.dirname(os.path.abspath(__file__))
    student = payload.get("student", {})
    favorites_csv = payload.get("favorites_csv", "student_id,program_name,uni_name\n")
    feedback_csv = payload.get("feedback_csv", "student_id,program_name,uni_name,rating,is_relevant\n")

    with tempfile.NamedTemporaryFile("w", suffix=".csv", delete=False, encoding="utf-8") as favorites_file:
        favorites_file.write(favorites_csv)
        favorites_path = favorites_file.name

    with tempfile.NamedTemporaryFile("w", suffix=".csv", delete=False, encoding="utf-8") as feedback_file:
        feedback_file.write(feedback_csv)
        feedback_path = feedback_file.name

    try:
        programs, _, favorites, feedback = load_and_clean_data(
            os.path.join(script_dir, "Programs_encoded.csv"),
            os.path.join(script_dir, "students_updated.csv"),
            favorites_path,
            feedback_path,
        )
        recommendations = recommend_top3(student, programs, favorites, feedback)
    finally:
        for path in [favorites_path, feedback_path]:
            try:
                os.unlink(path)
            except OSError:
                pass

    cleaned = []
    for index, item in enumerate(recommendations[:3], start=1):
        explanation = item.get("explanation", [])
        if not isinstance(explanation, list):
            explanation = [str(explanation)]

        program_row = item.get("_program_row")
        cleaned.append({
            "rank": index,
            "program_name": item.get("program_name", ""),
            "university": item.get("university", ""),
            "country": item.get("country", ""),
            "program_level": get_program_field(program_row, "Study Level"),
            "study_mode": get_program_field(program_row, "Study Mode"),
            "course_intensity": get_program_field(program_row, "Course Intensity"),
            "program_url": get_program_field(program_row, "Program URL"),
            "score": item.get("score", 0),
            "summary": item.get("summary", ""),
            "explanation": explanation,
        })

    return cleaned


class RecommendationHandler(BaseHTTPRequestHandler):
    def send_json(self, status, data):
        body = json.dumps(data, ensure_ascii=True).encode("utf-8")
        self.send_response(status)
        self.send_header("Content-Type", "application/json")
        self.send_header("Content-Length", str(len(body)))
        self.end_headers()
        self.wfile.write(body)

    def do_GET(self):
        if self.path.rstrip("/") == "/health":
            self.send_json(200, {"status": "ok"})
            return

        self.send_json(404, {"error": "not found"})

    def do_POST(self):
        if self.path.rstrip("/") != "/recommend":
            self.send_json(404, {"error": "not found"})
            return

        if not authorized(self.headers):
            self.send_json(401, {"error": "unauthorized"})
            return

        try:
            length = int(self.headers.get("Content-Length", "0"))
            raw_body = self.rfile.read(length).decode("utf-8")
            payload = json.loads(raw_body or "{}")
            self.send_json(200, build_recommendations(payload))
        except Exception as exc:
            self.send_json(500, {"error": str(exc)})

    def log_message(self, format, *args):
        return


def main():
    host = os.environ.get("RECOMMENDER_API_HOST", "127.0.0.1")
    port = int(os.environ.get("RECOMMENDER_API_PORT", "5001"))
    server = HTTPServer((host, port), RecommendationHandler)
    print(f"Recommendation API running on http://{host}:{port}", flush=True)
    server.serve_forever()


if __name__ == "__main__":
    main()
