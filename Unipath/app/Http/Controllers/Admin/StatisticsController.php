<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\University;
use App\Models\Program;
use App\Models\Category;
use App\Models\Message;
use App\Models\SuccessStory;
use App\Models\QuizAttempt;
use App\Models\QuizAttemptResults;
use App\Models\FeedbackRecommendation;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index()
    {
        return view('Admin.statistics', [

            // ========================
            // Overall Insights
            // ========================
            'totalUsers' => User::count(),
            'totalStudents' => Student::count(),
            'totalUniversities' => University::count(),
            'totalPrograms' => Program::count(),

            // ========================
            // Student Insights
            // ========================
            'academicLevels' => Student::select('academic_level', DB::raw('COUNT(*) as total'))
                ->groupBy('academic_level')
                ->get(),

            'preferredCountries' => Student::all()
                ->flatMap(fn (Student $student) => $student->preferenceValues('preferred_location') ?: ['Not specified'])
                ->countBy()
                ->map(fn ($total, $preferredLocation) => (object) [
                    'preferred_location' => $preferredLocation,
                    'total' => $total,
                ])
                ->values(),

            'preferredCategories' => Category::select('categories.name', DB::raw('COUNT(*) as total'))
                ->leftJoin('programs', 'categories.id', '=', 'programs.category_id')
                ->groupBy('categories.id', 'categories.name')
                ->orderByDesc('total')
                ->get(),

            'budgetRanges' => Student::select('budget', DB::raw('COUNT(*) as total'))
                ->groupBy('budget')
                ->get(),

            // ========================
            // Recommendation Analytics
            // ========================
            'averageRating' => round(FeedbackRecommendation::avg('rating'), 1),
            'relevantRecommendations' => FeedbackRecommendation::where('is_relevant', true)->count(),
            'irrelevantRecommendations' => FeedbackRecommendation::where('is_relevant', false)->count(),

            // ========================
            // Quiz Analytics
            // ========================
            'totalQuizAttempts' => QuizAttempt::count(),

            'completedQuizAttempts' => QuizAttempt::whereNotNull('completed_at')->count(),

            'mostMatchedMajor' => QuizAttemptResults::select('majors.name', DB::raw('COUNT(*) as total'))
                ->join('majors', 'quiz_attempt_results.major_id', '=', 'majors.id')
                ->groupBy('majors.id', 'majors.name')
                ->orderByDesc('total')
                ->first(),

            // ========================
            // Engagement
            // ========================
            'totalFavorites' => DB::table('favorites')->count(), 
            'totalMessages' => Message::count(),
            'totalSuccessStories' => SuccessStory::count(),
        ]);
    }
}
