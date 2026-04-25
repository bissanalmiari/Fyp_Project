<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Category;
use App\Models\Language;
use App\Models\Skill;
use App\Models\QuizAttempt;
use App\Models\SubCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    // Show personal info form
    public function personal()
    {
        $user = Auth::user();

        $student = Student::firstOrCreate(['user_id' => $user->id], ['name' => $user->name, 'email' => $user->email]);

       
        $languages= Language::all();
 
    $student->load('languages');
        return view('student.personal', compact('student', 'user', 'languages'));
    }
    

  
    public function personalstore(Request $request)
    {
        // Get the authenticated student
        $user = Auth::user();

        $student = Student::firstOrCreate(
            ['user_id' => $user->id],
            [
                'name' => $user->name,
                'email' => $user->email,
            ]
        );

        // Validate the fields
        $request->validate([
            'dob' => 'nullable|date',
            'nationality' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'languages' => 'nullable|array',
        ]);

        // Update student profile
        $student->dob = $request->dob;
        $student->nationality = $request->nationality;
        $student->country = $request->country;
        $student->city = $request->city;

        // Handle profile image safely
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            // Delete old image if exists
            if ($student->image) {
                Storage::disk('public')->delete($student->image);
            }

            // Store new image
            $path = $request->file('image')->store('students', 'public');
            $student->image = $path;
        }

            $student->languages()->sync($request->input('languages', []));
        $student->save();

        return redirect()->back()->with('success', 'Personal information updated successfully.');
    }

    // Show academic info form
    public function academic()
    {
        $user = Auth::user();

        $student = Student::firstOrCreate(
            ['user_id' => $user->id],
            [
                'name' => $user->name,
                'email' => $user->email,
            ]
        );
        return view('student.academic', compact('student'));
    }

    // Save academic info
   public function academicStore(Request $request)
{
    $student = Student::firstOrCreate(
        ['user_id' => Auth::id()],
        [
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
        ]
    );

    $request->validate([
        'academic_level' => 'required|string',
        'major' => 'nullable|string|max:255',
        'gpa' => 'nullable|numeric',
        'ielts' => 'nullable|numeric',
        'toefl' => 'nullable|numeric',
        'sat' => 'nullable|numeric',
        'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
    ]);

    // Save normal fields
    $student->academic_level = $request->academic_level;
    $student->major = $request->major;
    $student->gpa = $request->gpa;
    $student->ielts = $request->ielts;
    $student->toefl = $request->toefl;
    $student->sat = $request->sat;

    // Save image ONLY if exists
    if ($request->hasFile('image')) {

        if ($student->image) {
            Storage::disk('public')->delete($student->image);
        }

        $path = $request->file('image')->store('students', 'public');

        // IMPORTANT: only assign if successful
        if ($path) {
            $student->image = $path;
        }
    }

    $student->save();

    return redirect()->back()->with('success', 'Academic information updated successfully.');
}

    // Show preferences form
    public function preferences()
    {
        $user = Auth::user();

        $student = Student::firstOrCreate(
            ['user_id' => $user->id],
            [
                'name' => $user->name,
                'email' => $user->email,
            ]
        );
        return view('student.preferences', compact('student'));
    }

    // Save preferences
    public function preferencesStore(Request $request)
    {
        $user = Auth::user();

        $student = Student::firstOrCreate(
            ['user_id' => $user->id],
            [
                'name' => $user->name,
                'email' => $user->email,
            ]
        );

        $request->validate([
            'preferred_location' => 'nullable|string|max:255',
            'preferred_study_mode' => 'nullable|string|max:255',
            'preferred_course_intensity' => 'nullable|string|max:255',
            'budget' => 'nullable|string|max:255',
             'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $student->preferred_location = $request->preferred_location;
        $student->preferred_study_mode = $request->preferred_study_mode;
        $student->preferred_course_intensity = $request->preferred_course_intensity;
        $student->budget = $request->budget;

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            // Delete old image if exists
            if ($student->image) {
                Storage::disk('public')->delete($student->image);
            }

            // Store new image
            $path = $request->file('image')->store('students', 'public');
            $student->image = $path;
        }

        $student->save();

        return redirect()->back()->with('success', 'Preferences information updated successfully.');
    }

    public function professional()
    {
        $user = Auth::user();

        $student = Student::firstOrCreate(
            ['user_id' => $user->id],
            [
                'name' => $user->name,
                'email' => $user->email,
            ]
        );
        $categories = Category::with('subcategories')->get();
        
      $student->load('categories');      
        return view('student.professional', compact('student','categories'));
    }

    public function professionalstore(Request $request)
    {
        // Get the authenticated student
        $user = Auth::user();
        $student = Student::firstOrCreate(
            ['user_id' => $user->id],
            [
                'name' => $user->name,
                'email' => $user->email,
            ]
        );

        // Validate the fields
        $request->validate([
            'subcategories' => 'nullable|array',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'interests' => 'nullable|array',
            
        ]);


        // Handle profile image safely
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            // Delete old image if exists
            if ($student->image) {
                Storage::disk('public')->delete($student->image);
            }

            // Store new image
            $path = $request->file('image')->store('students', 'public');
            $student->image = $path;
        }
        
      $student->save();


$interests = $request->input('interests', []);
$subcategories = $request->input('subcategories', []);

// sync
$student->categories()->sync($interests);
$student->subcategories()->sync($subcategories);

        return redirect()->back()->with('success', 'Personal information updated successfully.');
    }

    // Show favorite items
    public function favorite()
    {
        $user = Auth::user();

        $student = Student::firstOrCreate(
            ['user_id' => $user->id],
            [
                'name' => $user->name,
                'email' => $user->email,
            ]
        );
        $favorites = $student->favorites()->with(['programs'])->get();
         
        return view('student.favorite', compact('student', 'favorites'));
    }

    // Show quiz history
    public function quizHistory()
    {
        $user = Auth::user();

        $student = Student::firstOrCreate(
            ['user_id' => $user->id],
            [
                'name' => $user->name,
                'email' => $user->email,
            ]
        );

        $attempts = QuizAttempt::with([
                'quiz',
                'answers.quizOption',
                'answers.quizQuestion',
                'quizAttemptResults.major'
            ])
            ->where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('student.quiz-history', compact('attempts'));
    }
    
}