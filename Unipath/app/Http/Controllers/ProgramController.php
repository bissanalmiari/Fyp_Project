<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\University;
use App\Models\Program;
use App\Models\Student;

class ProgramController extends Controller
{
    public function index(Request $request)
    {
        //
    }

    public function byUniversity(Request $request, string $id)
    {
        $query = Program::with(['category', 'requirement', 'languages'])
            ->where('university_id', $id);

        if ($request->filled('searchProgram')) {
            $query->where('name', 'ilike', '%' . trim($request->searchProgram) . '%');
        }

        if ($request->filled('category')) {
            $categoryName = $request->category;

            $query->whereHas('category', function ($q) use ($categoryName) {
                $q->where('name', $categoryName);
            });
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('intensity')) {
            $query->where('course_intensity', $request->intensity);
        }

        if ($request->filled('mode')) {
            $query->where('study_mode', $request->mode);
        }

        $university = University::findOrFail($id);

        $programs = $query
            ->orderBy('name')
            ->paginate(10)
            ->appends($request->query());

        $favoriteProgramIds = collect();

        if (Auth::check()) {
            $student = Student::where('user_id', Auth::id())->first();

            if ($student) {
                $favoriteProgramIds = DB::table('favorites')
                    ->where('student_id', $student->id)
                    ->pluck('program_id');
            }
        }

        return view('UniversityPages.partials.programTable', [
            'programs'           => $programs,
            'university'         => $university,
            'favoriteProgramIds' => $favoriteProgramIds,
        ])->render();
    }

    public function toggleFavorite(Request $request, Program $program)
    {
        if (!Auth::check()) {
            return response()->json([
                'saved' => false,
                'requires_login' => true,
                'login_url' => route('login'),
                'message' => 'Please login to save programs.',
            ], 401);
        }

        $student = Student::where('user_id', Auth::id())->first();

        if (!$student) {
            return response()->json([
                'saved' => false,
                'message' => 'Student profile not found.',
            ], 404);
        }

        $existing = DB::table('favorites')
            ->where('student_id', $student->id)
            ->where('program_id', $program->id)
            ->first();

        if ($existing) {
            DB::table('favorites')
                ->where('student_id', $student->id)
                ->where('program_id', $program->id)
                ->delete();

            return response()->json([
                'saved' => false,
                'message' => 'Program removed from favorites.',
            ]);
        }

        DB::table('favorites')->insert([
            'student_id' => $student->id,
            'program_id' => $program->id,
        ]);

        return response()->json([
            'saved' => true,
            'message' => 'Program added to favorites.',
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}