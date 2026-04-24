<?php

namespace App\Http\Controllers;

use App\Models\FeedbackRecommendation;
use App\Models\Student;
use App\Services\ProgramRecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicRecommendationController extends Controller
{
    public function index(ProgramRecommendationService $recommendationService)
    {
        return view('recommendations.public', $this->pageData($recommendationService));
    }

    public function generate(ProgramRecommendationService $recommendationService)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $student = $this->student();

        if (! $recommendationService->canGenerate($student)) {
            return redirect()
                ->route('public.recommendations')
                ->with('success', 'Your recommendation is already up to date for your current dashboard information.');
        }

        $recommendationService->generate($student);

        return redirect()
            ->route('public.recommendations')
            ->with('success', 'Your recommendation was generated from your dashboard information.');
    }

    public function feedback(Request $request, ProgramRecommendationService $recommendationService)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'rate' => 'required|in:low,medium,high',
        ]);

        $recommendations = $this->visibleRecommendations($this->student(), $recommendationService);
        [$rating, $isRelevant] = match ($validated['rate']) {
            'low' => [2, false],
            'medium' => [3, true],
            'high' => [5, true],
        };

        foreach ($recommendations as $recommendation) {
            FeedbackRecommendation::updateOrCreate(
                ['recommendation_id' => $recommendation->id],
                [
                    'rating' => $rating,
                    'is_relevant' => $isRelevant,
                ]
            );
        }

        return redirect()
            ->route('public.recommendations')
            ->with('success', 'Thank you. Your recommendation feedback was saved.');
    }

    private function pageData(ProgramRecommendationService $recommendationService): array
    {
        $student = Auth::check() ? $this->student() : null;
        $recommendations = $student
            ? $this->visibleRecommendations($student, $recommendationService)
            : collect();
        $confidence = $recommendations->isNotEmpty()
            ? (int) round($recommendations->avg('score'))
            : null;
        $nextAvailableAt = $student ? $recommendationService->nextAvailableAt($student) : null;
        $feedbackRate = $this->feedbackRate($recommendations);

        return [
            'student' => $student,
            'recommendations' => $recommendations,
            'confidence' => $confidence,
            'confidenceLabel' => $this->confidenceLabel($confidence),
            'feedbackRate' => $feedbackRate,
            'currentFeedbackRate' => $feedbackRate,
            'profileReadiness' => $student ? $this->profileReadiness($student) : null,
            'lastGeneratedAt' => $recommendations->max('created_at'),
            'signalSummary' => $student ? $this->signalSummary($student) : $this->guestSignalSummary(),
            'canGenerate' => $student ? $recommendationService->canGenerate($student) : false,
            'nextAvailableAt' => $nextAvailableAt,
            'refreshTooltip' => $nextAvailableAt
                ? 'You can recreate another recommendation ' . $nextAvailableAt->diffForHumans()
                : 'Generate a recommendation from your dashboard information.',
            'isSignedIn' => Auth::check(),
        ];
    }

    private function visibleRecommendations(Student $student, ProgramRecommendationService $recommendationService)
    {
        $current = $recommendationService->latestRecommendations($student);

        if ($current->isNotEmpty()) {
            return $current;
        }

        $latestHash = $student->recommendations()
            ->latest()
            ->value('preference_hash');

        if (! $latestHash) {
            return collect();
        }

        return $recommendationService->latestRecommendations($student, $latestHash);
    }

    private function student(): Student
    {
        $user = Auth::user();

        return Student::firstOrCreate(
            ['user_id' => $user->id],
            [
                'name' => $user->name,
                'email' => $user->email,
            ]
        );
    }

    private function confidenceLabel(?int $confidence): string
    {
        if ($confidence === null) {
            return 'Available after sign in';
        }

        if ($confidence >= 75) {
            return 'High confidence';
        }

        if ($confidence >= 50) {
            return 'Medium confidence';
        }

        return 'Low confidence';
    }

    private function feedbackRate($recommendations): ?string
    {
        if ($recommendations->isEmpty()) {
            return null;
        }

        $average = FeedbackRecommendation::whereIn('recommendation_id', $recommendations->pluck('id'))
            ->avg('rating');

        if ($average === null) {
            return null;
        }

        if ($average >= 4) {
            return 'high';
        }

        if ($average >= 3) {
            return 'medium';
        }

        return 'low';
    }

    private function profileReadiness(Student $student): array
    {
        $student->loadMissing('categories', 'subcategories', 'favorites');

        $checks = [
            'Academic level' => filled($student->academic_level),
            'Major' => filled($student->major),
            'Preferences' => filled($student->preferred_location) || filled($student->preferred_study_mode) || filled($student->preferred_course_intensity),
            'Budget' => filled($student->budget),
            'Interests' => $student->categories->isNotEmpty(),
            'Skills' => $student->subcategories->isNotEmpty(),
            'Scores' => filled($student->gpa) || filled($student->ielts) || filled($student->toefl) || filled($student->sat),
            'Favorites or feedback' => $student->favorites->isNotEmpty()
                || FeedbackRecommendation::whereHas('recommendation', fn ($query) => $query->where('student_id', $student->id))->exists(),
        ];

        $completed = collect($checks)->filter()->count();

        return [
            'percent' => (int) round(($completed / count($checks)) * 100),
            'completed' => $completed,
            'total' => count($checks),
            'checks' => $checks,
        ];
    }

    private function signalSummary(Student $student): array
    {
        $student->loadMissing('categories', 'subcategories', 'favorites');

        return [
            [
                'label' => 'Academic Profile',
                'value' => trim(($student->academic_level ?: 'Level not set') . ' / ' . ($student->major ?: 'Major not set')),
            ],
            [
                'label' => 'Preferences',
                'value' => ($student->preferred_location ?: 'Any country') . ' / ' . ($student->preferred_study_mode ?: 'Any mode') . ' / ' . ($student->preferred_course_intensity ?: 'Any intensity'),
            ],
            [
                'label' => 'Interests',
                'value' => $student->categories->pluck('name')->take(3)->implode(', ') ?: 'No interests selected yet',
            ],
            [
                'label' => 'Skills',
                'value' => $student->subcategories->pluck('name')->take(3)->implode(', ') ?: 'No skills selected yet',
            ],
            [
                'label' => 'Academic Scores',
                'value' => 'GPA ' . ($student->gpa ?? '--') . ' / IELTS ' . ($student->ielts ?? '--') . ' / TOEFL ' . ($student->toefl ?? '--') . ' / SAT ' . ($student->sat ?? '--'),
            ],
            [
                'label' => 'Personalization',
                'value' => $student->favorites->count() . ' favorites plus saved recommendation feedback',
            ],
        ];
    }

    private function guestSignalSummary(): array
    {
        return [
            ['label' => 'Academic Profile', 'value' => 'Degree level, major, GPA, IELTS, TOEFL, and SAT'],
            ['label' => 'Preferences', 'value' => 'Country, study mode, intensity, and budget'],
            ['label' => 'Interests', 'value' => 'Broad categories selected in the dashboard'],
            ['label' => 'Skills', 'value' => 'Detailed subcategories selected in the dashboard'],
            ['label' => 'Personalization', 'value' => 'Favorites and recommendation feedback after sign in'],
            ['label' => 'Explainability', 'value' => 'Each result includes the reasons behind the match'],
        ];
    }
}
