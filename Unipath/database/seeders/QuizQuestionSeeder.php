<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizOption;

class QuizQuestionSeeder extends Seeder
{
    public function run(): void
    {
        $quiz = Quiz::where('title', 'Major Recommendation Quiz')->firstOrFail();

        $existingQuestionIds = QuizQuestion::where('quiz_id', $quiz->id)->pluck('id');
        QuizOption::whereIn('quiz_question_id', $existingQuestionIds)->delete();
        QuizQuestion::where('quiz_id', $quiz->id)->delete();

        $questions = [
            [
                'track_key' => 'core',
                'order_index' => 1,
                'question_text' => 'What type of activities do you enjoy the most?',
                'options' => [
                    'Solving analytical problems',
                    'Managing or leading a team',
                    'Working on creative projects',
                    'Helping and caring for others',
                ],
            ],
            [
                'track_key' => 'core',
                'order_index' => 2,
                'question_text' => 'Which school subject do you enjoy the most?',
                'options' => [
                    'Mathematics',
                    'Computer / technology',
                    'Business / economics',
                    'Biology / social sciences',
                ],
            ],
            [
                'track_key' => 'core',
                'order_index' => 3,
                'question_text' => 'What kind of task sounds most interesting?',
                'options' => [
                    'Writing code or solving a technical problem',
                    'Organizing a project or making decisions',
                    'Designing or creating something visual',
                    'Supporting people through a challenge',
                ],
            ],
            [
                'track_key' => 'core',
                'order_index' => 4,
                'question_text' => 'What motivates you most in a future career?',
                'options' => [
                    'Innovation and problem-solving',
                    'Leadership and financial success',
                    'Creativity and self-expression',
                    'Helping people and making an impact',
                ],
            ],

            [
                'track_key' => 'tech',
                'order_index' => 1,
                'question_text' => 'Which task sounds most enjoyable?',
                'options' => [
                    'Building a program or app',
                    'Protecting systems and networks',
                    'Analyzing data and finding patterns',
                    'Working with computer hardware',
                ],
            ],
            [
                'track_key' => 'tech',
                'order_index' => 2,
                'question_text' => 'What do you prefer?',
                'options' => [
                    'Coding and logic',
                    'System security and networks',
                    'Data analysis and intelligent tools',
                    'Understanding computer components',
                ],
            ],
            [
                'track_key' => 'tech',
                'order_index' => 3,
                'question_text' => 'What kind of problem would you rather solve?',
                'options' => [
                    'A programming bug',
                    'A security or access issue',
                    'A data pattern or prediction problem',
                    'A hardware performance issue',
                ],
            ],
            [
                'track_key' => 'tech',
                'order_index' => 4,
                'question_text' => 'What sounds most interesting to you?',
                'options' => [
                    'Algorithms and programming',
                    'Digital security and system protection',
                    'Data patterns and intelligent systems',
                    'Computer devices and components',
                ],
            ],
            [
                'track_key' => 'tech',
                'order_index' => 5,
                'question_text' => 'What would you enjoy learning more?',
                'options' => [
                    'Programming languages',
                    'Network security and systems',
                    'Data analysis and machine learning',
                    'Digital electronics',
                ],
            ],
            [
                'track_key' => 'tech',
                'order_index' => 6,
                'question_text' => 'What environment suits you best?',
                'options' => [
                    'Writing code',
                    'Protecting systems and networks',
                    'Working with data and intelligent tools',
                    'Working with hardware and embedded systems',
                ],
            ],

     
            [
                'track_key' => 'business',
                'order_index' => 1,
                'question_text' => 'Which activity sounds best?',
                'options' => [
                    'Leading a team',
                    'Studying market trends',
                    'Working with numbers and money',
                    'Understanding how economies work',
                ],
            ],
            [
                'track_key' => 'business',
                'order_index' => 2,
                'question_text' => 'What interests you most?',
                'options' => [
                    'Management',
                    'Branding and promotion',
                    'Investment and finance',
                    'Economic analysis',
                ],
            ],
            [
                'track_key' => 'business',
                'order_index' => 3,
                'question_text' => 'Which task would you choose?',
                'options' => [
                    'Running a company project',
                    'Creating a marketing campaign',
                    'Planning a budget',
                    'Studying business and market behavior',
                ],
            ],
            [
                'track_key' => 'business',
                'order_index' => 4,
                'question_text' => 'What sounds most rewarding?',
                'options' => [
                    'Leading people',
                    'Selling an idea',
                    'Managing financial decisions',
                    'Understanding economic systems',
                ],
            ],
            [
                'track_key' => 'business',
                'order_index' => 5,
                'question_text' => 'What are you strongest at?',
                'options' => [
                    'Leadership',
                    'Communication and persuasion',
                    'Numerical decision-making',
                    'Analyzing trends',
                ],
            ],
            [
                'track_key' => 'business',
                'order_index' => 6,
                'question_text' => 'What workplace sounds best?',
                'options' => [
                    'Business office',
                    'Marketing agency',
                    'Financial institution',
                    'Research or policy environment',
                ],
            ],


            [
                'track_key' => 'health_social',
                'order_index' => 1,
                'question_text' => 'Which activity sounds most meaningful?',
                'options' => [
                    'Caring for patients',
                    'Understanding human behavior',
                    'Teaching or mentoring others',
                    'Studying living organisms',
                ],
            ],
            [
                'track_key' => 'health_social',
                'order_index' => 2,
                'question_text' => 'What interests you most?',
                'options' => [
                    'Health care',
                    'Mental health',
                    'Learning and teaching',
                    'Biological science',
                ],
            ],
            [
                'track_key' => 'health_social',
                'order_index' => 3,
                'question_text' => 'Which task would you prefer?',
                'options' => [
                    'Assisting in patient care',
                    'Talking to someone through a challenge',
                    'Explaining ideas to students',
                    'Studying biological systems',
                ],
            ],
            [
                'track_key' => 'health_social',
                'order_index' => 4,
                'question_text' => 'What kind of impact do you want?',
                'options' => [
                    'Improve health and wellbeing',
                    'Support emotional wellbeing',
                    'Help people learn and grow',
                    'Understand life and science',
                ],
            ],
            [
                'track_key' => 'health_social',
                'order_index' => 5,
                'question_text' => 'What are you strongest at?',
                'options' => [
                    'Patience and care',
                    'Listening and empathy',
                    'Communication and guidance',
                    'Curiosity about science',
                ],
            ],
            [
                'track_key' => 'health_social',
                'order_index' => 6,
                'question_text' => 'What environment sounds best?',
                'options' => [
                    'Hospital or clinic',
                    'Counseling or support setting',
                    'School or learning environment',
                    'Lab or science-based setting',
                ],
            ],

       
            [
                'track_key' => 'creative_analytical',
                'order_index' => 1,
                'question_text' => 'What sounds most enjoyable?',
                'options' => [
                    'Designing visuals and brand ideas',
                    'Solving abstract problems and formulas',
                    'Planning spaces and structures creatively',
                    'Creating digital media and interactive content',
                ],
            ],
            [
                'track_key' => 'creative_analytical',
                'order_index' => 2,
                'question_text' => 'What do you prefer?',
                'options' => [
                    'Art and visual communication',
                    'Numbers and logical reasoning',
                    'Creative planning and structural design',
                    'Animation, video, or digital experiences',
                ],
            ],
            [
                'track_key' => 'creative_analytical',
                'order_index' => 3,
                'question_text' => 'What kind of project would you choose?',
                'options' => [
                    'A logo or branding design',
                    'A mathematical puzzle',
                    'A building or space concept',
                    'A multimedia campaign or digital story',
                ],
            ],
            [
                'track_key' => 'creative_analytical',
                'order_index' => 4,
                'question_text' => 'What sounds most satisfying?',
                'options' => [
                    'Expressing ideas visually',
                    'Finding the correct logical answer',
                    'Turning ideas into structured designs',
                    'Combining creativity with digital tools',
                ],
            ],
            [
                'track_key' => 'creative_analytical',
                'order_index' => 5,
                'question_text' => 'What are you strongest at?',
                'options' => [
                    'Creativity and visual style',
                    'Analytical thinking',
                    'Planning and design structure',
                    'Digital creativity and media tools',
                ],
            ],
            [
                'track_key' => 'creative_analytical',
                'order_index' => 6,
                'question_text' => 'What future work sounds best?',
                'options' => [
                    'A design studio or branding agency',
                    'An academic or analytical field',
                    'An architecture or design firm',
                    'A multimedia or digital content company',
                ],
            ],
        ];

        foreach ($questions as $questionData) {
            $question = QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question_text' => $questionData['question_text'],
                'track_key' => $questionData['track_key'],
                'order_index' => $questionData['order_index'],
                'is_active' => true,
            ]);

            foreach ($questionData['options'] as $optionIndex => $optionText) {
                QuizOption::create([
                    'quiz_question_id' => $question->id,
                    'option_text' => $optionText,
                    'order_index' => $optionIndex + 1,
                ]);
            }
        }
    }
}