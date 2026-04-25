<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Major;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizOption;
use App\Models\QuizOptionMajorScores;

class QuizOptionMajorScoreSeeder extends Seeder
{
    public function run(): void
    {
        $quiz = Quiz::where('title', 'Major Recommendation Quiz')->firstOrFail();

        $majors = Major::whereIn('name', [
            'Computer Science',
            'Data Science',
            'Cybersecurity',
            'Software Engineering',

            'Computer Engineering',
            'Mechanical Engineering',
            'Electrical Engineering',
            'Industrial Engineering',

            'Business Administration',
            'Marketing',
            'Finance',
            'Economics',

            'Nursing',
            'Dentistry',
            'Pharmacy',
            'Medical Laboratory Sciences',

            'Graphic Design',
            'Multimedia Design',
            'Interior Design',
            'Architecture',

            'Mathematics',
            'Physics',
            'Chemistry',
            'Biology',

            'Psychology',
            'Social Sciences',
            'Sociology',
            'Education',
        ])->get()->keyBy('name');

        $getQuestion = function (string $trackKey, int $questionOrder) use ($quiz) {
            return QuizQuestion::where('quiz_id', $quiz->id)
                ->where('track_key', $trackKey)
                ->where('order_index', $questionOrder)
                ->firstOrFail();
        };

        $getOption = function (QuizQuestion $question, int $optionOrder) {
            return QuizOption::where('quiz_question_id', $question->id)
                ->where('order_index', $optionOrder)
                ->firstOrFail();
        };

        $assignScores = function (string $trackKey, int $questionOrder, int $optionOrder, array $scores) use ($getQuestion, $getOption, $majors) {
            $question = $getQuestion($trackKey, $questionOrder);
            $option = $getOption($question, $optionOrder);

            foreach ($scores as $majorName => $scoreValue) {
                $major = $majors->get($majorName);

                if (! $major) {
                    throw new \Exception("Major not found: {$majorName}");
                }

                QuizOptionMajorScores::updateOrCreate(
                    [
                        'quiz_option_id' => $option->id,
                        'major_id' => $major->id,
                    ],
                    [
                        'score_value' => $scoreValue,
                    ]
                );
            }
        };

        $assignScores('tech', 1, 1, [
            'Computer Science' => 3,
            'Data Science' => 1,
        ]);
        $assignScores('tech', 1, 2, [
            'Cybersecurity' => 3,
            'Software Engineering' => 1,
        ]);
        $assignScores('tech', 1, 3, [
            'Data Science' => 3,
            'Computer Science' => 1,
        ]);
        $assignScores('tech', 1, 4, [
            'Software Engineering' => 3,
        ]);

        $assignScores('tech', 2, 1, [
            'Computer Science' => 3,
            'Data Science' => 1,
        ]);
        $assignScores('tech', 2, 2, [
            'Cybersecurity' => 3,
            'Software Engineering' => 1,
        ]);
        $assignScores('tech', 2, 3, [
            'Data Science' => 3,
            'Computer Science' => 1,
        ]);
        $assignScores('tech', 2, 4, [
            'Software Engineering' => 3,
        ]);

        $assignScores('tech', 3, 1, [
            'Computer Science' => 3,
        ]);
        $assignScores('tech', 3, 2, [
            'Cybersecurity' => 3,
            'Software Engineering' => 1,
        ]);
        $assignScores('tech', 3, 3, [
            'Data Science' => 3,
            'Computer Science' => 1,
        ]);
        $assignScores('tech', 3, 4, [
            'Software Engineering' => 3,
        ]);

        $assignScores('tech', 4, 1, [
            'Computer Science' => 3,
        ]);
        $assignScores('tech', 4, 2, [
            'Cybersecurity' => 3,
        ]);
        $assignScores('tech', 4, 3, [
            'Data Science' => 3,
            'Computer Science' => 1,
        ]);
        $assignScores('tech', 4, 4, [
            'Software Engineering' => 3,
        ]);

        $assignScores('tech', 5, 1, [
            'Computer Science' => 3,
        ]);
        $assignScores('tech', 5, 2, [
            'Cybersecurity' => 3,
        ]);
        $assignScores('tech', 5, 3, [
            'Data Science' => 3,
        ]);
        $assignScores('tech', 5, 4, [
            'Software Engineering' => 3,
        ]);

        $assignScores('tech', 6, 1, [
            'Computer Science' => 3,
        ]);
        $assignScores('tech', 6, 2, [
            'Cybersecurity' => 3,
        ]);
        $assignScores('tech', 6, 3, [
            'Data Science' => 3,
        ]);
        $assignScores('tech', 6, 4, [
            'Software Engineering' => 3,
        ]);

        $assignScores('engineering', 1, 1, [
            'Computer Engineering' => 3,
            'Electrical Engineering' => 1,
        ]);
        $assignScores('engineering', 1, 2, [
            'Mechanical Engineering' => 3,
            'Industrial Engineering' => 1,
        ]);
        $assignScores('engineering', 1, 3, [
            'Electrical Engineering' => 3,
            'Computer Engineering' => 1,
        ]);
        $assignScores('engineering', 1, 4, [
            'Industrial Engineering' => 3,
            'Mechanical Engineering' => 1,
        ]);

        $assignScores('engineering', 2, 1, [
            'Computer Engineering' => 3,
        ]);
        $assignScores('engineering', 2, 2, [
            'Mechanical Engineering' => 3,
        ]);
        $assignScores('engineering', 2, 3, [
            'Electrical Engineering' => 3,
        ]);
        $assignScores('engineering', 2, 4, [
            'Industrial Engineering' => 3,
        ]);

        $assignScores('engineering', 3, 1, [
            'Computer Engineering' => 3,
        ]);
        $assignScores('engineering', 3, 2, [
            'Mechanical Engineering' => 3,
        ]);
        $assignScores('engineering', 3, 3, [
            'Electrical Engineering' => 3,
        ]);
        $assignScores('engineering', 3, 4, [
            'Industrial Engineering' => 3,
        ]);

        $assignScores('engineering', 4, 1, [
            'Computer Engineering' => 3,
        ]);
        $assignScores('engineering', 4, 2, [
            'Mechanical Engineering' => 3,
        ]);
        $assignScores('engineering', 4, 3, [
            'Electrical Engineering' => 3,
        ]);
        $assignScores('engineering', 4, 4, [
            'Industrial Engineering' => 3,
        ]);

        $assignScores('engineering', 5, 1, [
            'Computer Engineering' => 3,
        ]);
        $assignScores('engineering', 5, 2, [
            'Mechanical Engineering' => 3,
        ]);
        $assignScores('engineering', 5, 3, [
            'Electrical Engineering' => 3,
        ]);
        $assignScores('engineering', 5, 4, [
            'Industrial Engineering' => 3,
        ]);

        $assignScores('engineering', 6, 1, [
            'Computer Engineering' => 3,
        ]);
        $assignScores('engineering', 6, 2, [
            'Mechanical Engineering' => 3,
        ]);
        $assignScores('engineering', 6, 3, [
            'Electrical Engineering' => 3,
        ]);
        $assignScores('engineering', 6, 4, [
            'Industrial Engineering' => 3,
        ]);

        
        $assignScores('business', 1, 1, [
            'Business Administration' => 3,
            'Marketing' => 1,
        ]);
        $assignScores('business', 1, 2, [
            'Marketing' => 3,
            'Business Administration' => 1,
        ]);
        $assignScores('business', 1, 3, [
            'Finance' => 3,
            'Economics' => 1,
        ]);
        $assignScores('business', 1, 4, [
            'Economics' => 3,
            'Finance' => 1,
        ]);

        
        $assignScores('business', 2, 1, [
            'Business Administration' => 3,
        ]);
        $assignScores('business', 2, 2, [
            'Marketing' => 3,
        ]);
        $assignScores('business', 2, 3, [
            'Finance' => 3,
        ]);
        $assignScores('business', 2, 4, [
            'Economics' => 3,
        ]);

        
        $assignScores('business', 3, 1, [
            'Business Administration' => 3,
        ]);
        $assignScores('business', 3, 2, [
            'Marketing' => 3,
        ]);
        $assignScores('business', 3, 3, [
            'Finance' => 3,
        ]);
        $assignScores('business', 3, 4, [
            'Economics' => 3,
        ]);

        
        $assignScores('business', 4, 1, [
            'Business Administration' => 3,
            'Marketing' => 1,
        ]);
        $assignScores('business', 4, 2, [
            'Marketing' => 3,
            'Business Administration' => 1,
        ]);
        $assignScores('business', 4, 3, [
            'Finance' => 3,
            'Economics' => 1,
        ]);
        $assignScores('business', 4, 4, [
            'Economics' => 3,
            'Finance' => 1,
        ]);

        
        $assignScores('business', 5, 1, [
            'Business Administration' => 3,
        ]);
        $assignScores('business', 5, 2, [
            'Marketing' => 3,
        ]);
        $assignScores('business', 5, 3, [
            'Finance' => 3,
        ]);
        $assignScores('business', 5, 4, [
            'Economics' => 3,
        ]);

        
        $assignScores('business', 6, 1, [
            'Business Administration' => 3,
        ]);
        $assignScores('business', 6, 2, [
            'Marketing' => 3,
        ]);
        $assignScores('business', 6, 3, [
            'Finance' => 3,
        ]);
        $assignScores('business', 6, 4, [
            'Economics' => 3,
        ]);

        
        $assignScores('health', 1, 1, [
            'Nursing' => 3,
        ]);
        $assignScores('health', 1, 2, [
            'Dentistry' => 3,
        ]);
        $assignScores('health', 1, 3, [
            'Pharmacy' => 3,
        ]);
        $assignScores('health', 1, 4, [
            'Medical Laboratory Sciences' => 3,
        ]);

        $assignScores('health', 2, 1, [
            'Nursing' => 3,
        ]);
        $assignScores('health', 2, 2, [
            'Dentistry' => 3,
        ]);
        $assignScores('health', 2, 3, [
            'Pharmacy' => 3,
        ]);
        $assignScores('health', 2, 4, [
            'Medical Laboratory Sciences' => 3,
        ]);

        $assignScores('health', 3, 1, [
            'Nursing' => 3,
        ]);
        $assignScores('health', 3, 2, [
            'Dentistry' => 3,
        ]);
        $assignScores('health', 3, 3, [
            'Pharmacy' => 3,
        ]);
        $assignScores('health', 3, 4, [
            'Medical Laboratory Sciences' => 3,
        ]);

        $assignScores('health', 4, 1, [
            'Nursing' => 3,
        ]);
        $assignScores('health', 4, 2, [
            'Dentistry' => 3,
        ]);
        $assignScores('health', 4, 3, [
            'Pharmacy' => 3,
        ]);
        $assignScores('health', 4, 4, [
            'Medical Laboratory Sciences' => 3,
        ]);

        $assignScores('health', 5, 1, [
            'Nursing' => 3,
        ]);
        $assignScores('health', 5, 2, [
            'Dentistry' => 3,
        ]);
        $assignScores('health', 5, 3, [
            'Pharmacy' => 3,
        ]);
        $assignScores('health', 5, 4, [
            'Medical Laboratory Sciences' => 3,
        ]);

        $assignScores('health', 6, 1, [
            'Nursing' => 3,
        ]);
        $assignScores('health', 6, 2, [
            'Dentistry' => 3,
        ]);
        $assignScores('health', 6, 3, [
            'Pharmacy' => 3,
        ]);
        $assignScores('health', 6, 4, [
            'Medical Laboratory Sciences' => 3,
        ]);

        $assignScores('creative', 1, 1, [
            'Graphic Design' => 3,
            'Multimedia Design' => 1,
        ]);
        $assignScores('creative', 1, 2, [
            'Multimedia Design' => 3,
            'Graphic Design' => 1,
        ]);
        $assignScores('creative', 1, 3, [
            'Interior Design' => 3,
            'Architecture' => 1,
        ]);
        $assignScores('creative', 1, 4, [
            'Architecture' => 3,
            'Interior Design' => 1,
        ]);

        $assignScores('creative', 2, 1, [
            'Graphic Design' => 3,
        ]);
        $assignScores('creative', 2, 2, [
            'Multimedia Design' => 3,
        ]);
        $assignScores('creative', 2, 3, [
            'Interior Design' => 3,
        ]);
        $assignScores('creative', 2, 4, [
            'Architecture' => 3,
        ]);

        $assignScores('creative', 3, 1, [
            'Graphic Design' => 3,
        ]);
        $assignScores('creative', 3, 2, [
            'Multimedia Design' => 3,
        ]);
        $assignScores('creative', 3, 3, [
            'Interior Design' => 3,
        ]);
        $assignScores('creative', 3, 4, [
            'Architecture' => 3,
        ]);

        $assignScores('creative', 4, 1, [
            'Graphic Design' => 3,
        ]);
        $assignScores('creative', 4, 2, [
            'Multimedia Design' => 3,
        ]);
        $assignScores('creative', 4, 3, [
            'Interior Design' => 3,
        ]);
        $assignScores('creative', 4, 4, [
            'Architecture' => 3,
        ]);

        $assignScores('creative', 5, 1, [
            'Graphic Design' => 3,
        ]);
        $assignScores('creative', 5, 2, [
            'Multimedia Design' => 3,
        ]);
        $assignScores('creative', 5, 3, [
            'Interior Design' => 3,
        ]);
        $assignScores('creative', 5, 4, [
            'Architecture' => 3,
        ]);

        $assignScores('creative', 6, 1, [
            'Graphic Design' => 3,
        ]);
        $assignScores('creative', 6, 2, [
            'Multimedia Design' => 3,
        ]);
        $assignScores('creative', 6, 3, [
            'Interior Design' => 3,
        ]);
        $assignScores('creative', 6, 4, [
            'Architecture' => 3,
        ]);

        $assignScores('science', 1, 1, [
            'Mathematics' => 3,
        ]);
        $assignScores('science', 1, 2, [
            'Physics' => 3,
        ]);
        $assignScores('science', 1, 3, [
            'Chemistry' => 3,
        ]);
        $assignScores('science', 1, 4, [
            'Biology' => 3,
        ]);

        $assignScores('science', 2, 1, [
            'Mathematics' => 3,
        ]);
        $assignScores('science', 2, 2, [
            'Physics' => 3,
        ]);
        $assignScores('science', 2, 3, [
            'Chemistry' => 3,
        ]);
        $assignScores('science', 2, 4, [
            'Biology' => 3,
        ]);

        $assignScores('science', 3, 1, [
            'Mathematics' => 3,
        ]);
        $assignScores('science', 3, 2, [
            'Physics' => 3,
        ]);
        $assignScores('science', 3, 3, [
            'Chemistry' => 3,
        ]);
        $assignScores('science', 3, 4, [
            'Biology' => 3,
        ]);

        $assignScores('science', 4, 1, [
            'Mathematics' => 3,
        ]);
        $assignScores('science', 4, 2, [
            'Physics' => 3,
        ]);
        $assignScores('science', 4, 3, [
            'Chemistry' => 3,
        ]);
        $assignScores('science', 4, 4, [
            'Biology' => 3,
        ]);

        $assignScores('science', 5, 1, [
            'Mathematics' => 3,
        ]);
        $assignScores('science', 5, 2, [
            'Physics' => 3,
        ]);
        $assignScores('science', 5, 3, [
            'Chemistry' => 3,
        ]);
        $assignScores('science', 5, 4, [
            'Biology' => 3,
        ]);

        $assignScores('science', 6, 1, [
            'Mathematics' => 3,
        ]);
        $assignScores('science', 6, 2, [
            'Physics' => 3,
        ]);
        $assignScores('science', 6, 3, [
            'Chemistry' => 3,
        ]);
        $assignScores('science', 6, 4, [
            'Biology' => 3,
        ]);

        $assignScores('social', 1, 1, [
            'Psychology' => 3,
        ]);
        $assignScores('social', 1, 2, [
            'Sociology' => 3,
        ]);
        $assignScores('social', 1, 3, [
            'Social Sciences' => 3,
        ]);
        $assignScores('social', 1, 4, [
            'Education' => 3,
        ]);

        $assignScores('social', 2, 1, [
            'Psychology' => 3,
        ]);
        $assignScores('social', 2, 2, [
            'Sociology' => 3,
        ]);
        $assignScores('social', 2, 3, [
            'Social Sciences' => 3,
        ]);
        $assignScores('social', 2, 4, [
            'Education' => 3,
        ]);

        $assignScores('social', 3, 1, [
            'Psychology' => 3,
        ]);
        $assignScores('social', 3, 2, [
            'Sociology' => 3,
        ]);
        $assignScores('social', 3, 3, [
            'Social Sciences' => 3,
        ]);
        $assignScores('social', 3, 4, [
            'Education' => 3,
        ]);

        $assignScores('social', 4, 1, [
            'Psychology' => 3,
        ]);
        $assignScores('social', 4, 2, [
            'Sociology' => 3,
        ]);
        $assignScores('social', 4, 3, [
            'Social Sciences' => 3,
        ]);
        $assignScores('social', 4, 4, [
            'Education' => 3,
        ]);

        $assignScores('social', 5, 1, [
            'Psychology' => 3,
        ]);
        $assignScores('social', 5, 2, [
            'Sociology' => 3,
        ]);
        $assignScores('social', 5, 3, [
            'Social Sciences' => 3,
        ]);
        $assignScores('social', 5, 4, [
            'Education' => 3,
        ]);

        $assignScores('social', 6, 1, [
            'Psychology' => 3,
        ]);
        $assignScores('social', 6, 2, [
            'Sociology' => 3,
        ]);
        $assignScores('social', 6, 3, [
            'Social Sciences' => 3,
        ]);
        $assignScores('social', 6, 4, [
            'Education' => 3,
        ]);
    }
}