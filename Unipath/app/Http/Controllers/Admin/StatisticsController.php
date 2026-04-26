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
            'totalCategories' => Category::count(),
            'totalSubCategories' => DB::table('subcategories')->count(),

            // ========================
            // Student Insights
            // ========================
            'academicLevels' => Student::select('academic_level', DB::raw('COUNT(*) as total'))
                ->groupBy('academic_level')
                ->get(),

            'preferredCountries' => Student::select('preferred_location', DB::raw('COUNT(*) as total'))
                ->groupBy('preferred_location')
                ->get(),

            'preferredCategories' => DB::table('student_subcategory')
                ->join('subcategories', 'student_subcategory.subcategory_id', '=', 'subcategories.id')
                ->join('categories', 'subcategories.category_id', '=', 'categories.id')
                ->select('categories.name', DB::raw('COUNT(*) as total'))
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