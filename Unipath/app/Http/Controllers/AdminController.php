<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Student;
use App\Models\User;
use App\Models\Career;
use App\Models\Category;
use App\Models\QuizQuestion;
use App\Models\Language;
use App\Models\Program;
use App\Models\Progrem_Requirement;
use App\Models\SubCategory;
use App\Models\University;


class AdminController extends Controller
{
    public function users(Request $request)
{
    $query = Student::with('user')->whereHas('user');

    if ($request->search) {
        $search = strtolower($request->search);

        $query->whereHas('user', function ($q) use ($search) {
            $q->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"]);
        });
    }

    $students = $query->paginate(6);

    if ($request->ajax()) {
        return response()->json([
            'html' => view('Admin.users.partials.cards', compact('students'))->render(),
            'pagination' => $students->links('pagination::tailwind')->toHtml(),
            'count' => $students->total(),
        ]);
    }

    return view('Admin.users.users', compact('students'));
}

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->student()?->delete();
        $user->delete();

        return redirect()->route('Admin.users')->with('success', 'User deleted successfully.');
    }
    
   public function careers(Request $request)
{
    $query = Career::query();

    if ($request->search) {
  $query->whereRaw('LOWER(title) LIKE ?', [
        '%' . strtolower($request->search) . '%'
    ]);
    }

    if ($request->category_id) {
        $query->where('category_id', $request->category_id);
    }

    $careers = $query->with('category')->paginate(9);
    $categories = Category::all();

    if ($request->ajax()) {
        return response()->json([
        'html' => view('Admin.career.partials.cards', compact('careers'))->render(),
        'pagination' => $careers->links('pagination::tailwind')->toHtml(),
        'count' => $careers->total(),
        'from' => $careers->firstItem(),
        'to' => $careers->lastItem(),
    ]);
    }

    return view('Admin.career.careers', compact('careers', 'categories'));
}

    public function createCareer()
    {
       
        $categories = Category::all();

        return view('Admin.career.form', compact('categories'));
    }

    
    public function storeCareer(Request $request)
    {
        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('careers', 'public');
        }

        $data['is_active'] = $request->has('is_active');
        $data['in_demand'] = $request->has('in_demand');

        Career::create($data);

        return redirect()->route('Admin.careers')
            ->with('success', 'Career added successfully.');
    }

    public function editCareer($id)
    {
       $career = Career::findOrFail($id);
        $categories = Category::all();

        return view('Admin.career.form', compact('career', 'categories'));

    }

    public function deleteCareer($id)
    {
        $career = Career::findOrFail($id);
        if ($career->image) {
        Storage::disk('public')->delete($career->image);
    }
        $career->delete();
        return redirect()->route('Admin.careers')->with('success', 'Career deleted successfully.');
    }


    public function updateCareer(Request $request, $id)
    {
        $career = Career::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('careers', 'public');
        }

        $data['is_active'] = $request->has('is_active');
        $data['in_demand'] = $request->has('in_demand');

        $career->update($data);

        return redirect()->route('Admin.careers')
            ->with('success', 'Career updated successfully.');
    }
    
    public function quiz()
    {
        $questions = QuizQuestion::with(['options.majorScores.major'])
            ->orderByRaw("
                CASE 
                    WHEN track_key = 'core' THEN 1
                    WHEN track_key = 'tech' THEN 2
                    WHEN track_key = 'engineering' THEN 3
                    WHEN track_key = 'business' THEN 4
                    WHEN track_key = 'health' THEN 5
                    WHEN track_key = 'creative' THEN 6
                    WHEN track_key = 'science' THEN 7
                    WHEN track_key = 'social' THEN 8
                    ELSE 9
                END
            ")
            ->orderBy('order_index')
            ->get()
            ->groupBy('track_key');

        return view('Admin.quiz.quiz', compact('questions'));
    }

    public function universities(Request $request)
    {
        $query = University::query();

        if ($request->filled('search')) {
            $query->whereRaw('LOWER(name) LIKE ?', [
                '%' . strtolower($request->search) . '%'
            ]);
        }

        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }

        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $universities = $query->orderBy('name')->paginate(9)->withQueryString();

        $countries = University::whereNotNull('country')
            ->distinct()
            ->orderBy('country')
            ->pluck('country');

        $cities = University::when($request->filled('country'), function ($q) use ($request) {
                $q->where('country', $request->country);
            })
            ->whereNotNull('city')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        $types = University::whereNotNull('type')
            ->distinct()
            ->orderBy('type')
            ->pluck('type');

        if ($request->ajax()) {
            return response()->json([
                'html' => view('Admin.university.partials.cards', compact('universities'))->render(),
                'pagination' => $universities->links('pagination::tailwind')->toHtml(),
                'count' => $universities->total(),
                'from' => $universities->firstItem(),
                'to' => $universities->lastItem(),
            ]);
        }

        return view('Admin.university.index', compact('universities', 'countries', 'cities', 'types'));
    }

    public function createUniversity()
    {
        $types = ['Public', 'Private'];

        return view('Admin.university.form', compact('types'));
    }

    public function storeUniversity(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'rank' => 'nullable|integer',
            'logo' => 'nullable|url|max:2048',
            'website_url' => 'nullable|url|max:255',
            'image' => 'nullable|url|max:2048',
            'backup_image' => 'nullable|url|max:2048',
            'description' => 'nullable|string',
            'type' => 'nullable|string|max:255',
            'insta' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
            'facebook' => 'nullable|url|max:255',
        ]);

        University::create($data);

        return redirect()->route('Admin.universities')->with('success', 'University added successfully.');
    }

    public function showUniversity($id)
    {
        $university = University::with('programs')->findOrFail($id);

        return view('Admin.university.show', compact('university'));
    }

    public function editUniversity($id)
    {
        $university = University::findOrFail($id);
        $types = ['Public', 'Private'];

        return view('Admin.university.form', compact('university', 'types'));
    }

    public function updateUniversity(Request $request, $id)
    {
        $university = University::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'rank' => 'nullable|integer',
            'logo' => 'nullable|url|max:2048',
            'website_url' => 'nullable|url|max:255',
            'image' => 'nullable|url|max:2048',
            'backup_image' => 'nullable|url|max:2048',
            'description' => 'nullable|string',
            'type' => 'nullable|string|max:255',
            'insta' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
            'facebook' => 'nullable|url|max:255',
        ]);

        $university->update($data);

        return redirect()->route('Admin.universities')->with('success', 'University updated successfully.');
    }

    public function deleteUniversity($id)
    {
        $university = University::with('programs.languages')->findOrFail($id);

        DB::transaction(function () use ($university) {
            foreach ($university->programs as $program) {
                $program->languages()->detach();
                $program->delete();
            }

            $university->delete();
        });

        return redirect()->route('Admin.universities')->with('success', 'University deleted successfully.');
    }

    public function programs(Request $request)
    {
        $query = Program::with(['university', 'category', 'subcategory', 'languages', 'requirement']);

        if ($request->filled('search')) {
            $query->whereRaw('LOWER(name) LIKE ?', [
                '%' . strtolower($request->search) . '%'
            ]);
        }

        if ($request->filled('university_id')) {
            $query->where('university_id', $request->university_id);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('subcategory_id')) {
            $query->where('subcategory_id', $request->subcategory_id);
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('study_mode')) {
            $query->where('study_mode', $request->study_mode);
        }

        if ($request->filled('course_intensity')) {
            $query->where('course_intensity', $request->course_intensity);
        }

        $programs = $query->orderBy('name')->paginate(6)->withQueryString();

        $universities = University::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $subcategories = SubCategory::with('category')->orderBy('name')->get();

        $levels = Program::whereNotNull('level')
            ->distinct()
            ->orderBy('level')
            ->pluck('level');

        $studyModes = collect(['Online', 'On Campus', 'Hybrid']);

        $courseIntensities = Program::whereNotNull('course_intensity')
            ->distinct()
            ->orderBy('course_intensity')
            ->pluck('course_intensity');

        if ($request->ajax()) {
            return response()->json([
                'html' => view('Admin.program.partials.cards', compact('programs'))->render(),
                'pagination' => $programs->links('pagination::tailwind')->toHtml(),
                'count' => $programs->total(),
                'from' => $programs->firstItem(),
                'to' => $programs->lastItem(),
            ]);
        }

        return view('Admin.program.index', compact(
            'programs',
            'universities',
            'categories',
            'subcategories',
            'levels',
            'studyModes',
            'courseIntensities'
        ));
    }

    public function createProgram()
    {
        $universities = University::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $subcategories = SubCategory::with('category')->orderBy('name')->get();
        $languages = Language::orderBy('name')->get();

        $levels = ['Bachelor', 'Master'];
        $studyModes = ['On Campus', 'Online', 'Hybrid'];
        $courseIntensities = ['Full Time', 'Part Time'];

        return view('Admin.program.form', compact(
            'universities',
            'categories',
            'subcategories',
            'languages',
            'levels',
            'studyModes',
            'courseIntensities'
        ));
    }

    private function resolveProgramRequirementId(Request $request)
    {
        $requirementData = [
            'sat' => $request->filled('sat') ? $request->sat : null,
            'ielts' => $request->filled('ielts') ? $request->ielts : null,
            'toefl' => $request->filled('toefl') ? $request->toefl : null,
            'minimum_gpa' => $request->filled('minimum_gpa') ? $request->minimum_gpa : null,
        ];

        if (
            is_null($requirementData['sat']) &&
            is_null($requirementData['ielts']) &&
            is_null($requirementData['toefl']) &&
            is_null($requirementData['minimum_gpa'])
        ) {
            return null;
        }

        return Progrem_Requirement::firstOrCreate($requirementData)->id;
    }

    public function storeProgram(Request $request)
    {
        $data = $request->validate([
            'university_id' => 'required|exists:universities,id',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'name' => 'required|string|max:255',
            'course_intensity' => 'nullable|string|max:255',
            'level' => 'nullable|string|max:255',
            'url' => 'nullable|url|max:255',
            'study_mode' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:255',
            'eu_fees' => 'nullable|numeric',
            'non_eu_fees' => 'nullable|numeric',
            'arab_fees' => 'nullable|numeric',
            'leb_fees' => 'nullable|numeric',
            'pal_fees' => 'nullable|numeric',
            'us_fees' => 'nullable|numeric',
            'language_ids' => 'nullable|array',
            'language_ids.*' => 'exists:languages,id',
            'sat' => 'nullable|numeric',
            'ielts' => 'nullable|numeric',
            'toefl' => 'nullable|numeric',
            'minimum_gpa' => 'nullable|numeric',
        ]);

        if (!empty($data['subcategory_id'])) {
            $validSubcategory = SubCategory::where('id', $data['subcategory_id'])
                ->where('category_id', $data['category_id'])
                ->exists();

            if (!$validSubcategory) {
                return back()
                    ->withErrors(['subcategory_id' => 'Selected sub category does not belong to the selected category.'])
                    ->withInput();
            }
        }

        $data['program_requirments_id'] = $this->resolveProgramRequirementId($request);
        unset($data['language_ids']);

        $program = Program::create($data);

        $languageIds = array_values(array_unique($request->input('language_ids', [])));
        $program->languages()->sync($languageIds);

        return redirect()->route('Admin.programs')->with('success', 'Program added successfully.');
    }

    public function showProgram($id)
    {
        $program = Program::with(['university', 'category', 'subcategory', 'languages', 'requirement'])->findOrFail($id);

        return view('Admin.program.show', compact('program'));
    }

    public function editProgram($id)
    {
        $program = Program::with(['languages', 'requirement', 'subcategory'])->findOrFail($id);

        $universities = University::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $subcategories = SubCategory::with('category')->orderBy('name')->get();
        $languages = Language::orderBy('name')->get();

        $levels = ['Bachelor', 'Master'];
        $studyModes = ['On Campus', 'Online', 'Hybrid'];
        $courseIntensities = ['Full Time', 'Part Time'];

        return view('Admin.program.form', compact(
            'program',
            'universities',
            'categories',
            'subcategories',
            'languages',
            'levels',
            'studyModes',
            'courseIntensities'
        ));
    }

    public function updateProgram(Request $request, $id)
    {
        $program = Program::findOrFail($id);

        $data = $request->validate([
            'university_id' => 'required|exists:universities,id',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'name' => 'required|string|max:255',
            'course_intensity' => 'nullable|string|max:255',
            'level' => 'nullable|string|max:255',
            'url' => 'nullable|url|max:255',
            'study_mode' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:255',
            'eu_fees' => 'nullable|numeric',
            'non_eu_fees' => 'nullable|numeric',
            'arab_fees' => 'nullable|numeric',
            'leb_fees' => 'nullable|numeric',
            'pal_fees' => 'nullable|numeric',
            'us_fees' => 'nullable|numeric',
            'language_ids' => 'nullable|array',
            'language_ids.*' => 'exists:languages,id',
            'sat' => 'nullable|numeric',
            'ielts' => 'nullable|numeric',
            'toefl' => 'nullable|numeric',
            'minimum_gpa' => 'nullable|numeric',
        ]);

        if (!empty($data['subcategory_id'])) {
            $validSubcategory = SubCategory::where('id', $data['subcategory_id'])
                ->where('category_id', $data['category_id'])
                ->exists();

            if (!$validSubcategory) {
                return back()
                    ->withErrors(['subcategory_id' => 'Selected sub category does not belong to the selected category.'])
                    ->withInput();
            }
        }

        $data['program_requirments_id'] = $this->resolveProgramRequirementId($request);
        unset($data['language_ids']);

        $program->update($data);

        $languageIds = array_values(array_unique($request->input('language_ids', [])));
        $program->languages()->sync($languageIds);

        return redirect()->route('Admin.programs')->with('success', 'Program updated successfully.');
    }

    public function deleteProgram($id)
    {
        $program = Program::findOrFail($id);
        $program->languages()->detach();
        $program->delete();

        return redirect()->route('Admin.programs')->with('success', 'Program deleted successfully.');
    }
    
    public function profile()
    {
        $admin = auth()->user();

        return view('Admin.profile.index', compact('admin'));
    }

    public function updateProfile(Request $request)
    {
        $admin = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $admin->id],
            'password' => ['nullable', 'confirmed', 'min:8'],
        ]);

        $admin->name = $validated['name'];
        $admin->email = $validated['email'];

        if (!empty($validated['password'])) {
            $admin->password = Hash::make($validated['password']);
        }

        $admin->save();

        return back()->with('success', 'Profile updated successfully.');
    }
}

