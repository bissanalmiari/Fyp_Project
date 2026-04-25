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
                    'Solving logical or technical problems',        // tech + engineering + science
                    'Managing projects or business ideas',          // business
                    'Creating or designing something',              // creative
                    'Helping or understanding people',              // health + social
                ],
            ],
            [
                'track_key' => 'core',
                'order_index' => 2,
                'question_text' => 'Which school subject do you enjoy the most?',
                'options' => [
                    'Math / Physics / Technology',                  // tech + engineering + science
                    'Business / Economics',                         // business
                    'Art / Design',                                 // creative
                    'Biology / Psychology / Social studies',        // health + social
                ],
            ],
            [
                'track_key' => 'core',
                'order_index' => 3,
                'question_text' => 'What kind of task sounds most interesting?',
                'options' => [
                    'Building or solving technical systems',        // tech + engineering + science
                    'Running or organizing projects',               // business
                    'Designing or creating visuals',                // creative
                    'Supporting or helping others',                 // health + social
                ],
            ],
            [
                'track_key' => 'core',
                'order_index' => 4,
                'question_text' => 'What motivates you most in a future career?',
                'options' => [
                    'Innovation and solving complex problems',      // tech + engineering + science
                    'Success, leadership, and money',               // business
                    'Creativity and expression',                    // creative
                    'Helping people and making an impact',          // health + social
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
                    'Designing and developing software systems',
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
                    'Designing software solutions',
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
                    'A software system design problem',
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
                    'Software architecture and system design',
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
                    'Software development methodologies',
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
                    'Building and maintaining software systems',
                ],
            ],

            [
                'track_key' => 'engineering',
                'order_index' => 1,
                'question_text' => 'Which task sounds most enjoyable?',
                'options' => [
                    'Designing computer-based systems',
                    'Working with machines and mechanical parts',
                    'Working with electrical power and circuits',
                    'Solving broad engineering problems',
                ],
            ],
            [
                'track_key' => 'engineering',
                'order_index' => 2,
                'question_text' => 'What do you prefer?',
                'options' => [
                    'Computers, processors, and embedded systems',
                    'Machines, motion, and manufacturing',
                    'Circuits, power, and electrical systems',
                    'General engineering design and problem-solving',
                ],
            ],
            [
                'track_key' => 'engineering',
                'order_index' => 3,
                'question_text' => 'What kind of problem would you rather solve?',
                'options' => [
                    'Improving a smart device or digital system',
                    'Improving the performance of a machine',
                    'Fixing an electrical system issue',
                    'Designing an efficient engineering solution',
                ],
            ],
            [
                'track_key' => 'engineering',
                'order_index' => 4,
                'question_text' => 'What sounds most interesting to you?',
                'options' => [
                    'Microprocessors and computer-controlled systems',
                    'Engines, mechanics, and production systems',
                    'Electricity, electronics, and power systems',
                    'Applying engineering principles to different fields',
                ],
            ],
            [
                'track_key' => 'engineering',
                'order_index' => 5,
                'question_text' => 'What would you enjoy learning more?',
                'options' => [
                    'Embedded systems and digital design',
                    'Thermodynamics and machine design',
                    'Circuit analysis and power distribution',
                    'General engineering methods and design thinking',
                ],
            ],
            [
                'track_key' => 'engineering',
                'order_index' => 6,
                'question_text' => 'What environment suits you best?',
                'options' => [
                    'Working on computer-integrated systems',
                    'Working in mechanical workshops or production settings',
                    'Working in electrical labs or power environments',
                    'Working on interdisciplinary engineering projects',
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
                'track_key' => 'health',
                'order_index' => 1,
                'question_text' => 'Which activity sounds most meaningful?',
                'options' => [
                    'Caring for patients',
                    'Diagnosing health problems',
                    'Working with medicines',
                    'Running lab tests and analysis',
                ],
            ],
            [
                'track_key' => 'health',
                'order_index' => 2,
                'question_text' => 'What interests you most?',
                'options' => [
                    'Patient care',
                    'Medical diagnosis',
                    'Pharmacy and drugs',
                    'Lab and medical testing',
                ],
            ],
            [
                'track_key' => 'health',
                'order_index' => 3,
                'question_text' => 'Which task would you prefer?',
                'options' => [
                    'Assisting patients',
                    'Examining and diagnosing',
                    'Preparing medications',
                    'Analyzing lab samples',
                ],
            ],
            [
                'track_key' => 'health',
                'order_index' => 4,
                'question_text' => 'What kind of impact do you want?',
                'options' => [
                    'Improve patient care',
                    'Diagnose diseases',
                    'Provide medical treatment',
                    'Support medical testing',
                ],
            ],
            [
                'track_key' => 'health',
                'order_index' => 5,
                'question_text' => 'What are you strongest at?',
                'options' => [
                    'Care and patience',
                    'Attention to detail',
                    'Accuracy with medicine',
                    'Scientific observation',
                ],
            ],
            [
                'track_key' => 'health',
                'order_index' => 6,
                'question_text' => 'What environment sounds best?',
                'options' => [
                    'Hospital',
                    'Clinic or dental center',
                    'Pharmacy',
                    'Medical lab',
                ],
            ],

            [
                'track_key' => 'creative',
                'order_index' => 1,
                'question_text' => 'What sounds most enjoyable?',
                'options' => [
                    'Designing visuals',
                    'Creating digital media',
                    'Designing interiors',
                    'Planning buildings',
                ],
            ],
            [
                'track_key' => 'creative',
                'order_index' => 2,
                'question_text' => 'What do you prefer?',
                'options' => [
                    'Graphic design',
                    'Multimedia and video',
                    'Interior spaces',
                    'Architecture',
                ],
            ],
            [
                'track_key' => 'creative',
                'order_index' => 3,
                'question_text' => 'What kind of project would you choose?',
                'options' => [
                    'Branding design',
                    'Digital content',
                    'Interior layout',
                    'Building concept',
                ],
            ],
            [
                'track_key' => 'creative',
                'order_index' => 4,
                'question_text' => 'What sounds most satisfying?',
                'options' => [
                    'Visual creativity',
                    'Digital storytelling',
                    'Designing spaces',
                    'Architectural design',
                ],
            ],
            [
                'track_key' => 'creative',
                'order_index' => 5,
                'question_text' => 'What are you strongest at?',
                'options' => [
                    'Creativity',
                    'Digital tools',
                    'Spatial thinking',
                    'Structural design',
                ],
            ],
            [
                'track_key' => 'creative',
                'order_index' => 6,
                'question_text' => 'What future work sounds best?',
                'options' => [
                    'Design agency',
                    'Media company',
                    'Interior design firm',
                    'Architecture firm',
                ],
            ],

            [
                'track_key' => 'science',
                'order_index' => 1,
                'question_text' => 'What sounds most interesting?',
                'options' => [
                    'Mathematical problems',
                    'Physical laws and forces',
                    'Chemical reactions',
                    'Biological systems',
                ],
            ],
            [
                'track_key' => 'science',
                'order_index' => 2,
                'question_text' => 'What do you prefer?',
                'options' => [
                    'Numbers and formulas',
                    'Physics concepts',
                    'Chemistry experiments',
                    'Biology and life science',
                ],
            ],
            [
                'track_key' => 'science',
                'order_index' => 3,
                'question_text' => 'Which task would you choose?',
                'options' => [
                    'Solving equations',
                    'Studying motion',
                    'Mixing chemicals',
                    'Studying organisms',
                ],
            ],
            [
                'track_key' => 'science',
                'order_index' => 4,
                'question_text' => 'What motivates you?',
                'options' => [
                    'Logic and math',
                    'Understanding the universe',
                    'Chemical discoveries',
                    'Understanding life',
                ],
            ],
            [
                'track_key' => 'science',
                'order_index' => 5,
                'question_text' => 'What are you strongest at?',
                'options' => [
                    'Problem solving',
                    'Conceptual thinking',
                    'Experimentation',
                    'Observation',
                ],
            ],
            [
                'track_key' => 'science',
                'order_index' => 6,
                'question_text' => 'What environment suits you best?',
                'options' => [
                    'Mathematics field',
                    'Physics lab',
                    'Chemistry lab',
                    'Biology lab',
                ],
            ],

            [
                'track_key' => 'social',
                'order_index' => 1,
                'question_text' => 'What sounds most interesting?',
                'options' => [
                    'Understanding behavior',
                    'Studying society',
                    'Analyzing social systems',
                    'Teaching others',
                ],
            ],
            [
                'track_key' => 'social',
                'order_index' => 2,
                'question_text' => 'What do you prefer?',
                'options' => [
                    'Psychology',
                    'Sociology',
                    'Social sciences',
                    'Education',
                ],
            ],
            [
                'track_key' => 'social',
                'order_index' => 3,
                'question_text' => 'Which task would you choose?',
                'options' => [
                    'Helping individuals',
                    'Studying groups',
                    'Researching society',
                    'Teaching students',
                ],
            ],
            [
                'track_key' => 'social',
                'order_index' => 4,
                'question_text' => 'What motivates you?',
                'options' => [
                    'Helping people',
                    'Understanding society',
                    'Social impact',
                    'Education and learning',
                ],
            ],
            [
                'track_key' => 'social',
                'order_index' => 5,
                'question_text' => 'What are you strongest at?',
                'options' => [
                    'Empathy',
                    'Observation',
                    'Analysis',
                    'Communication',
                ],
            ],
            [
                'track_key' => 'social',
                'order_index' => 6,
                'question_text' => 'What environment suits you best?',
                'options' => [
                    'Counseling setting',
                    'Research field',
                    'Social institutions',
                    'Schools',
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