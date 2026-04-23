<?php

namespace App\Http\Controllers;

use App\Models\Major;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\QuizAttempt;
use App\Models\QuizAttemptResults;
use App\Models\QuizQuestion;
use App\Models\Student;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;


class QuizController extends Controller
{
    private const CORE_COUNT = 4;

    private const TRACK_MAJOR_NAMES = [
        'tech' => [
            'Computer Science',
            'Data Science',
            'Cybersecurity',
            'Software Engineering',
        ],
        'engineering' => [
            'Computer Engineering',
            'Mechanical Engineering',
            'Electrical Engineering',
            'Industrial Engineering',
        ],
        'business' => [
            'Business Administration',
            'Marketing',
            'Finance',
            'Economics',
        ],
        'health_social' => [
            'Nursing',
            'Psychology',
            'Education',
            'Biology',
        ],
        'creative_analytical' => [
            'Graphic Design',
            'Mathematics',
            'Architecture',
            'Multimedia Design',
        ],
    ];

    public function start()
    {
        $quiz = Quiz::where('title', 'Major Recommendation Quiz')->firstOrFail();

        $studentId = null;

        if (auth()->check()) {
            $student = Student::where('user_id', auth()->id())->first();
            $studentId = $student?->id;
        }

        $attempt = QuizAttempt::create([
            'student_id' => $studentId,
            'quiz_id' => $quiz->id,
            'selected_track' => null,
        ]);

        return redirect()->route('quiz.question', [
            'attempt' => $attempt->id,
            'order' => 1,
        ]);
    }

    public function showQuestion($attemptId, $order)
    {
        $attempt = $this->getAccessibleAttemptOrFail($attemptId);

        $question = $this->resolveQuestionForOverallOrder($attempt, (int) $order);

        $existingAnswer = QuizAnswer::where('quiz_attempt_id', $attempt->id)
            ->where('quiz_question_id', $question->id)
            ->first();

        $displayQuestionNumber = (int) $order;
        $totalQuestions = $this->getTotalQuestionCount($attempt);
        $previousOrder = $displayQuestionNumber > 1 ? $displayQuestionNumber - 1 : null;

        return view('quiz.questions', compact(
            'attempt',
            'question',
            'existingAnswer',
            'displayQuestionNumber',
            'totalQuestions',
            'previousOrder'
        ));
    }

    public function storeAnswer(Request $request, $attemptId, $order)
    {
        $attempt = $this->getAccessibleAttemptOrFail($attemptId);
        $overallOrder = (int) $order;

        $question = $this->resolveQuestionForOverallOrder($attempt, $overallOrder);

        $validated = $request->validate([
            'quiz_option_id' => [
                'required',
                Rule::exists('quiz_options', 'id')->where(function ($query) use ($question) {
                    $query->where('quiz_question_id', $question->id);
                }),
            ],
        ]);

        QuizAnswer::updateOrCreate(
            [
                'quiz_attempt_id' => $attempt->id,
                'quiz_question_id' => $question->id,
            ],
            [
                'quiz_option_id' => $validated['quiz_option_id'],
            ]
        );

        if ($overallOrder < self::CORE_COUNT) {
            return redirect()->route('quiz.question', [
                'attempt' => $attempt->id,
                'order' => $overallOrder + 1,
            ]);
        }

        if ($overallOrder === self::CORE_COUNT && empty($attempt->selected_track)) {
            $selectedTrack = $this->determineSelectedTrack($attempt);

            $attempt->update([
                'selected_track' => $selectedTrack,
            ]);

            return redirect()->route('quiz.question', [
                'attempt' => $attempt->id,
                'order' => self::CORE_COUNT + 1,
            ]);
        }

        $totalQuestions = $this->getTotalQuestionCount($attempt);

        if ($overallOrder < $totalQuestions) {
            return redirect()->route('quiz.question', [
                'attempt' => $attempt->id,
                'order' => $overallOrder + 1,
            ]);
        }

        $attempt->update([
            'completed_at' => now(),
        ]);

        $this->calculateResults($attempt->fresh());

        return redirect()->route('quiz.completed', $attempt->id);
    }

    public function results($attemptId)
    {
        $attempt = $this->getAccessibleAttemptOrFail($attemptId)->load(['quizAttemptResults.major']);

        $results = $attempt->quizAttemptResults()
            ->with('major')
            ->orderBy('rank_position')
            ->get();

        $topResult = $results->first();
        $topMajorName = $topResult?->major?->name;

        $insightMap = [
            'Computer Science' => [
                'why' => 'Your answers showed strong interest in logic, problem-solving, and programming tasks.',
                'suggests' => 'This suggests you may enjoy a tech path focused on software, algorithms, and analytical thinking.',
                'next' => 'Explore courses like programming, algorithms, and databases, then compare them with your other top matches.',
            ],
            'Computer Engineering' => [
                'why' => 'Your answers showed interest in systems, hardware, and practical technology.',
                'suggests' => 'This suggests you may prefer a technical path that connects computing with devices and engineering.',
                'next' => 'Explore courses like digital systems, electronics, and computer architecture before making your final choice.',
            ],
            'Data Science' => [
                'why' => 'Your answers reflected strong interest in data, patterns, and analytical thinking.',
                'suggests' => 'This suggests you may enjoy working with data analysis, intelligent systems, and real-world insights.',
                'next' => 'Explore courses like statistics, machine learning, and data analysis, then compare them with your other top matches.',
            ],
            'Cybersecurity' => [
                'why' => 'Your answers showed interest in systems, protection, and solving digital security problems.',
                'suggests' => 'This suggests you may fit a technology path focused on securing networks, systems, and information.',
                'next' => 'Explore courses like network security, ethical hacking, and system protection before choosing.',
            ],
            'Business Administration' => [
                'why' => 'Your answers showed strength in leadership, planning, and decision-making.',
                'suggests' => 'This suggests you may do well in a business path involving management, organization, and strategy.',
                'next' => 'Explore courses like management, operations, and entrepreneurship, then compare them with your other top matches.',
            ],
            'Marketing' => [
                'why' => 'Your answers reflected communication, creativity, and interest in audiences and trends.',
                'suggests' => 'This suggests you may enjoy a business path focused on promotion, branding, and market behavior.',
                'next' => 'Explore courses like digital marketing, branding, and consumer behavior before deciding.',
            ],
            'Finance' => [
                'why' => 'Your answers showed strength in numbers, planning, and financial decision-making.',
                'suggests' => 'This suggests you may be suited to a path focused on budgeting, investment, and financial analysis.',
                'next' => 'Explore courses like accounting, investment, and corporate finance, then compare them with your other top matches.',
            ],
            'Economics' => [
                'why' => 'Your answers reflected interest in trends, systems, and analytical thinking.',
                'suggests' => 'This suggests you may enjoy understanding how markets, policies, and economic systems work.',
                'next' => 'Explore courses like microeconomics, macroeconomics, and policy analysis before choosing.',
            ],
            'Nursing' => [
                'why' => 'Your answers showed empathy, care, and interest in helping others directly.',
                'suggests' => 'This suggests you may fit a health-focused path centered on patient care and wellbeing.',
                'next' => 'Explore courses in nursing practice, health sciences, and clinical care before deciding.',
            ],
            'Psychology' => [
                'why' => 'Your answers reflected interest in people, behavior, and emotional support.',
                'suggests' => 'This suggests you may enjoy a path focused on understanding the mind and helping others.',
                'next' => 'Explore courses like developmental psychology, counseling, and behavioral science before choosing.',
            ],
            'Education' => [
                'why' => 'Your answers showed strength in communication, guidance, and helping others learn.',
                'suggests' => 'This suggests you may fit a path centered on teaching, mentoring, and learning support.',
                'next' => 'Explore courses in teaching methods, child development, and educational practice before deciding.',
            ],
            'Biology' => [
                'why' => 'Your answers reflected curiosity about science, life, and natural systems.',
                'suggests' => 'This suggests you may enjoy a science path focused on living organisms and research.',
                'next' => 'Explore courses like genetics, microbiology, and human biology, then compare them with your other top matches.',
            ],
            'Graphic Design' => [
                'why' => 'Your answers showed creativity, visual thinking, and interest in design.',
                'suggests' => 'This suggests you may fit a path focused on visual communication, branding, and creative projects.',
                'next' => 'Explore courses like typography, branding, and digital design, and review sample student portfolios too.',
            ],
            'Mathematics' => [
                'why' => 'Your answers reflected strong analytical thinking, logic, and pattern recognition.',
                'suggests' => 'This suggests you may enjoy a path focused on reasoning, abstract thinking, and problem-solving.',
                'next' => 'Explore courses like calculus, statistics, and applied mathematics before making your decision.',
            ],
            'Architecture' => [
                'why' => 'Your answers showed strength in creative planning, visual thinking, and structured design.',
                'suggests' => 'This suggests you may enjoy a path that combines creativity with spatial design and practical problem-solving.',
                'next' => 'Explore courses like architectural design, drafting, structures, and space planning before making your final choice.',
            ],

            'Multimedia Design' => [
                'why' => 'Your answers reflected digital creativity, visual storytelling, and interest in interactive media.',
                'suggests' => 'This suggests you may enjoy a creative path focused on digital design, motion, and multimedia experiences.',
                'next' => 'Explore courses like animation, digital media, interactive design, and multimedia production before choosing.',
            ],
            'Mechanical Engineering' => [
                'why' => 'Your answers showed interest in machines, systems, and how things work physically.',
                'suggests' => 'This suggests you may enjoy a path focused on mechanics, design, and engineering systems.',
                'next' => 'Explore courses like thermodynamics, mechanics, and machine design before deciding.',
            ],

            'Electrical Engineering' => [
                'why' => 'Your answers showed interest in electricity, circuits, and technical systems.',
                'suggests' => 'This suggests you may fit a path focused on electrical systems, power, and electronics.',
                'next' => 'Explore courses like circuit analysis, power systems, and electronics before choosing.',
            ],

            'Industrial Engineering' => [
                'why' => 'Your answers showed balanced interest across multiple technical and engineering areas.',
                'suggests' => 'This suggests you may prefer a flexible engineering path with broad applications.',
                'next' => 'Explore industrial engineering programs focusing on systems optimization, operations, and decision-making before deciding.',
            ],
        ];

        $dynamicInsights = $insightMap[$topMajorName] ?? [
            'why' => 'Your top matches reflect the interests and strengths you showed across the quiz.',
            'suggests' => 'These results can help you compare majors that may fit your personality, skills, and goals.',
            'next' => 'Explore course details, career paths, and compare your top matches before choosing.',
        ];

        return view('quiz.results', compact('attempt', 'results', 'topResult', 'dynamicInsights'));
    }

    public function completed($attemptId)
    {
        $attempt = $this->getAccessibleAttemptOrFail($attemptId);

        return view('quiz.completed', compact('attempt'));
    }

    private function resolveQuestionForOverallOrder(QuizAttempt $attempt, int $overallOrder): QuizQuestion
    {
        if ($overallOrder <= self::CORE_COUNT) {
            return QuizQuestion::with('options')
                ->where('quiz_id', $attempt->quiz_id)
                ->where('track_key', 'core')
                ->where('order_index', $overallOrder)
                ->where('is_active', true)
                ->firstOrFail();
        }

        if (empty($attempt->selected_track)) {
            abort(404, 'Track has not been selected yet.');
        }

        $branchOrder = $overallOrder - self::CORE_COUNT;

        return QuizQuestion::with('options')
            ->where('quiz_id', $attempt->quiz_id)
            ->where('track_key', $attempt->selected_track)
            ->where('order_index', $branchOrder)
            ->where('is_active', true)
            ->firstOrFail();
    }

    private function getTotalQuestionCount(QuizAttempt $attempt): int
    {
        if (empty($attempt->selected_track)) {
            return self::CORE_COUNT + 6;
        }

        $branchCount = QuizQuestion::where('quiz_id', $attempt->quiz_id)
            ->where('track_key', $attempt->selected_track)
            ->where('is_active', true)
            ->count();

        return self::CORE_COUNT + $branchCount;
    }

    private function determineSelectedTrack(QuizAttempt $attempt): string
    {
        $trackScores = [
            'tech' => 0,
            'engineering' => 0,
            'business' => 0,
            'health_social' => 0,
            'creative_analytical' => 0,
        ];

        $coreAnswers = QuizAnswer::with(['quizOption', 'quizQuestion'])
            ->where('quiz_attempt_id', $attempt->id)
            ->whereHas('quizQuestion', function ($query) {
                $query->where('track_key', 'core');
            })
            ->get();

        foreach ($coreAnswers as $answer) {
            $questionOrder = $answer->quizQuestion->order_index;
            $optionOrder = $answer->quizOption->order_index;

            if ($questionOrder === 1) {
                if ($optionOrder === 1) {
                    $trackScores['tech'] += 2;
                    $trackScores['engineering'] += 2;
                    $trackScores['creative_analytical'] += 2;
                } elseif ($optionOrder === 2) {
                    $trackScores['business'] += 3;
                } elseif ($optionOrder === 3) {
                    $trackScores['creative_analytical'] += 3;
                } elseif ($optionOrder === 4) {
                    $trackScores['health_social'] += 3;
                }
            }

            if ($questionOrder === 2) {
                if ($optionOrder === 1) {
                    $trackScores['creative_analytical'] += 3;
                    $trackScores['engineering'] += 2;
                    $trackScores['tech'] += 1;
                } elseif ($optionOrder === 2) {
                    $trackScores['tech'] += 3;
                } elseif ($optionOrder === 3) {
                    $trackScores['business'] += 3;
                } elseif ($optionOrder === 4) {
                    $trackScores['health_social'] += 3;
                }
            }

            if ($questionOrder === 3) {
                if ($optionOrder === 1) {
                    $trackScores['tech'] += 2;
                    $trackScores['engineering'] += 2;
                } elseif ($optionOrder === 2) {
                    $trackScores['business'] += 3;
                } elseif ($optionOrder === 3) {
                    $trackScores['creative_analytical'] += 3;
                } elseif ($optionOrder === 4) {
                    $trackScores['health_social'] += 3;
                }
            }

            if ($questionOrder === 4) {
                if ($optionOrder === 1) {
                    $trackScores['tech'] += 2;
                    $trackScores['engineering'] += 2;
                } elseif ($optionOrder === 2) {
                    $trackScores['business'] += 3;
                } elseif ($optionOrder === 3) {
                    $trackScores['creative_analytical'] += 3;
                } elseif ($optionOrder === 4) {
                    $trackScores['health_social'] += 3;
                }
            }
        }
        arsort($trackScores);
        return array_key_first($trackScores);
    }

    private function calculateResults(QuizAttempt $attempt): void
    {
        if (empty($attempt->selected_track)) {
            return;
        }

        $allowedMajorNames = self::TRACK_MAJOR_NAMES[$attempt->selected_track] ?? [];

        $allowedMajorIds = Major::whereIn('name', $allowedMajorNames)
            ->pluck('id')
            ->toArray();

        $answers = QuizAnswer::with(['quizOption.majorScores', 'quizQuestion'])
            ->where('quiz_attempt_id', $attempt->id)
            ->whereHas('quizQuestion', function ($query) use ($attempt) {
                $query->where('track_key', $attempt->selected_track);
            })
            ->get();

        $majorTotals = [];

        foreach ($allowedMajorIds as $majorId) {
            $majorTotals[$majorId] = 0;
        }

        foreach ($answers as $answer) {
            foreach ($answer->quizOption->majorScores as $majorScore) {
                if (! in_array($majorScore->major_id, $allowedMajorIds)) {
                    continue;
                }

                $majorTotals[$majorScore->major_id] += $majorScore->score_value;
            }
        }

        if (empty($majorTotals)) {
            return;
        }

        arsort($majorTotals);

        QuizAttemptResults::where('quiz_attempt_id', $attempt->id)->delete();

        $topThree = array_slice($majorTotals, 0, 3, true);
        $highestScore = max($majorTotals);

        $rank = 1;
        $previousScore = null;
        $previousCompatibility = null;

        foreach ($topThree as $majorId => $score) {
            $baseCompatibility = $highestScore > 0
                ? round(($score / $highestScore) * 95)
                : 0;

            if ($previousScore !== null && $score === $previousScore) {
                $compatibility = max(50, $previousCompatibility - 4);
            } else {
                $compatibility = $baseCompatibility;
            }

            QuizAttemptResults::create([
                'quiz_attempt_id' => $attempt->id,
                'major_id' => $majorId,
                'rank_position' => $rank,
                'score' => $score,
                'compatibility_percent' => $compatibility,
            ]);

            $previousScore = $score;
            $previousCompatibility = $compatibility;
            $rank++;
        }
    }

    private function getAccessibleAttemptOrFail($attemptId): QuizAttempt
    {
        $attempt = QuizAttempt::findOrFail($attemptId);

        if ($attempt->student_id !== null) {
            abort_unless(auth()->check(), 403);

            $student = Student::where('user_id', auth()->id())->first();

            abort_unless($student && $attempt->student_id === $student->id, 403);
        }

        return $attempt;
    }
    
    public function showMajor($slug)
    {
        $major = Major::where('slug', $slug)->firstOrFail();

        return view('quiz.major-details', compact('major'));
    }
}