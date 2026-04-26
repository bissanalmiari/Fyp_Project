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

        $recommendations = $recommendationService->generate($student);

        if ($recommendations->isEmpty()) {
            return redirect()
                ->route('public.recommendations')
                ->with('success', 'No programs matched your saved dashboard preferences. Try widening your country, study mode, course intensity, budget, or interests, then generate again.');
        }

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
            'recommendation_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'is_relevant' => 'required|boolean',
        ]);

        $recommendations = $this->visibleRecommendations($this->student(), $recommendationService);

        if (! $recommendations->contains('id', $validated['recommendation_id'])) {
            abort(403);
        }

        if (FeedbackRecommendation::where('recommendation_id', $validated['recommendation_id'])->exists()) {
            return redirect()
                ->to(route('public.recommendations') . '#recommendation-details')
                ->with('success', 'Your feedback for this program is already saved.');
        }

        FeedbackRecommendation::updateOrCreate(
            ['recommendation_id' => $validated['recommendation_id']],
            [
                'rating' => $validated['rating'],
                'is_relevant' => $validated['is_relevant'],
            ]
        );

        return redirect()
            ->to(route('public.recommendations') . '#recommendation-details')
            ->with('success', 'Thank you. Your program feedback was saved.');
    }

    private function pageData(ProgramRecommendationService $recommendationService): array
    {
        $student = Auth::check() ? $this->student() : null;
        $recommendations = $student
            ? $this->visibleRecommendations($student, $recommendationService)
            : collect();
        $nextAvailableAt = $student ? $recommendationService->nextAvailableAt($student) : null;

        return [
            'student' => $student,
            'recommendations' => $recommendations,
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
        return $recommendationService->latestRecommendations($student)->load('feedbacks');
    }

    private function student(): Student
    {
        $user = Auth::user();

        return Student::firstOrCreate(['user_id' => $user->id]);
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
