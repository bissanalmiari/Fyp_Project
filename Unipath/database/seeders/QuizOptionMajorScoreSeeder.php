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
            'Computer Engineering',
            'Data Science',
            'Cybersecurity',
            'Business Administration',
            'Marketing',
            'Finance',
            'Economics',
            'Nursing',
            'Psychology',
            'Education',
            'Biology',
            'Graphic Design',
            'Mathematics',
            'Architecture',
            'Multimedia Design',
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
            'Computer Engineering' => 1,
        ]);
        $assignScores('tech', 1, 3, [
            'Data Science' => 3,
            'Computer Science' => 1,
        ]);
        $assignScores('tech', 1, 4, [
            'Computer Engineering' => 3,
        ]);

        $assignScores('tech', 2, 1, [
            'Computer Science' => 3,
            'Data Science' => 1,
        ]);
        $assignScores('tech', 2, 2, [
            'Cybersecurity' => 3,
            'Computer Engineering' => 1,
        ]);
        $assignScores('tech', 2, 3, [
            'Data Science' => 3,
            'Computer Science' => 1,
        ]);
        $assignScores('tech', 2, 4, [
            'Computer Engineering' => 3,
        ]);

        $assignScores('tech', 3, 1, [
            'Computer Science' => 3,
        ]);
        $assignScores('tech', 3, 2, [
            'Cybersecurity' => 3,
            'Computer Engineering' => 1,
        ]);
        $assignScores('tech', 3, 3, [
            'Data Science' => 3,
            'Computer Science' => 1,
        ]);
        $assignScores('tech', 3, 4, [
            'Computer Engineering' => 3,
        ]);

        $assignScores('tech', 4, 1, [
            'Computer Science' => 3,
            'Mathematics' => 1,
        ]);
        $assignScores('tech', 4, 2, [
            'Cybersecurity' => 3,
        ]);
        $assignScores('tech', 4, 3, [
            'Data Science' => 3,
            'Computer Science' => 1,
        ]);
        $assignScores('tech', 4, 4, [
            'Computer Engineering' => 3,
        ]);

        $assignScores('tech', 5, 1, [
            'Computer Science' => 3,
        ]);
        $assignScores('tech', 5, 2, [
            'Cybersecurity' => 3,
        ]);
        $assignScores('tech', 5, 3, [
            'Data Science' => 3,
            'Mathematics' => 1,
        ]);
        $assignScores('tech', 5, 4, [
            'Computer Engineering' => 3,
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
            'Computer Engineering' => 3,
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

        

        
        $assignScores('health_social', 1, 1, [
            'Nursing' => 3,
            'Biology' => 1,
        ]);
        $assignScores('health_social', 1, 2, [
            'Psychology' => 3,
            'Education' => 1,
        ]);
        $assignScores('health_social', 1, 3, [
            'Education' => 3,
            'Psychology' => 1,
        ]);
        $assignScores('health_social', 1, 4, [
            'Biology' => 3,
            'Nursing' => 1,
        ]);

        
        $assignScores('health_social', 2, 1, [
            'Nursing' => 3,
        ]);
        $assignScores('health_social', 2, 2, [
            'Psychology' => 3,
        ]);
        $assignScores('health_social', 2, 3, [
            'Education' => 3,
        ]);
        $assignScores('health_social', 2, 4, [
            'Biology' => 3,
        ]);

        
        $assignScores('health_social', 3, 1, [
            'Nursing' => 3,
        ]);
        $assignScores('health_social', 3, 2, [
            'Psychology' => 3,
        ]);
        $assignScores('health_social', 3, 3, [
            'Education' => 3,
        ]);
        $assignScores('health_social', 3, 4, [
            'Biology' => 3,
        ]);

        
        $assignScores('health_social', 4, 1, [
            'Nursing' => 3,
        ]);
        $assignScores('health_social', 4, 2, [
            'Psychology' => 3,
        ]);
        $assignScores('health_social', 4, 3, [
            'Education' => 3,
        ]);
        $assignScores('health_social', 4, 4, [
            'Biology' => 3,
        ]);

        
        $assignScores('health_social', 5, 1, [
            'Nursing' => 3,
        ]);
        $assignScores('health_social', 5, 2, [
            'Psychology' => 3,
        ]);
        $assignScores('health_social', 5, 3, [
            'Education' => 3,
        ]);
        $assignScores('health_social', 5, 4, [
            'Biology' => 3,
        ]);

        
        $assignScores('health_social', 6, 1, [
            'Nursing' => 3,
        ]);
        $assignScores('health_social', 6, 2, [
            'Psychology' => 3,
        ]);
        $assignScores('health_social', 6, 3, [
            'Education' => 3,
        ]);
        $assignScores('health_social', 6, 4, [
            'Biology' => 3,
        ]);

        

        
        $assignScores('creative_analytical', 1, 1, [
            'Graphic Design' => 3,
            'Multimedia Design' => 1,
        ]);
        $assignScores('creative_analytical', 1, 2, [
            'Mathematics' => 3,
        ]);
        $assignScores('creative_analytical', 1, 3, [
            'Architecture' => 3,
            'Graphic Design' => 1,
        ]);
        $assignScores('creative_analytical', 1, 4, [
            'Multimedia Design' => 3,
            'Graphic Design' => 1,
        ]);

        $assignScores('creative_analytical', 2, 1, [
            'Graphic Design' => 3,
        ]);
        $assignScores('creative_analytical', 2, 2, [
            'Mathematics' => 3,
        ]);
        $assignScores('creative_analytical', 2, 3, [
            'Architecture' => 3,
        ]);
        $assignScores('creative_analytical', 2, 4, [
            'Multimedia Design' => 3,
        ]);

        $assignScores('creative_analytical', 3, 1, [
            'Graphic Design' => 3,
            'Multimedia Design' => 1,
        ]);
        $assignScores('creative_analytical', 3, 2, [
            'Mathematics' => 3,
        ]);
        $assignScores('creative_analytical', 3, 3, [
            'Architecture' => 3,
        ]);
        $assignScores('creative_analytical', 3, 4, [
            'Multimedia Design' => 3,
            'Graphic Design' => 1,
        ]);

        $assignScores('creative_analytical', 4, 1, [
            'Graphic Design' => 3,
        ]);
        $assignScores('creative_analytical', 4, 2, [
            'Mathematics' => 3,
        ]);
        $assignScores('creative_analytical', 4, 3, [
            'Architecture' => 3,
            'Mathematics' => 1,
        ]);
        $assignScores('creative_analytical', 4, 4, [
            'Multimedia Design' => 3,
        ]);

        $assignScores('creative_analytical', 5, 1, [
            'Graphic Design' => 3,
        ]);
        $assignScores('creative_analytical', 5, 2, [
            'Mathematics' => 3,
        ]);
        $assignScores('creative_analytical', 5, 3, [
            'Architecture' => 3,
        ]);
        $assignScores('creative_analytical', 5, 4, [
            'Multimedia Design' => 3,
        ]);

        $assignScores('creative_analytical', 6, 1, [
            'Graphic Design' => 3,
        ]);
        $assignScores('creative_analytical', 6, 2, [
            'Mathematics' => 3,
        ]);
        $assignScores('creative_analytical', 6, 3, [
            'Architecture' => 3,
        ]);
        $assignScores('creative_analytical', 6, 4, [
            'Multimedia Design' => 3,
        ]);
    }
}