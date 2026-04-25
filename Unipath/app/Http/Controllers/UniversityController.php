<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\University;
use App\Models\Program;
use App\Models\Category;
use App\Models\Language;
use App\Models\Student;

class UniversityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  public function index(Request $request)
    {
        $query = University::query();

        // Search
        if ($request->filled('search')) {
            $query->where('name', 'ilike', '%' . trim($request->search) . '%');
        }

        // Country
        $selectedCountry = $request->country;
        if ($request->filled('country')) {
            $query->where('country', $selectedCountry);
        }

        // City
        $selectedCity = $request->city;
        if ($request->filled('city')) {
            $query->where('city', $selectedCity);
        }

        // Rank
        $selectedRank = $request->rank;
        if ($request->filled('rank')) {
            if ($selectedRank === "Top 100") {
                $query->whereBetween('rank', [1, 100]);
            } elseif ($selectedRank === "101-300") {
                $query->whereBetween('rank', [101, 300]);
            } elseif ($selectedRank === "301-500") {
                $query->whereBetween('rank', [301, 500]);
            } elseif ($selectedRank === "501-1500") {
                $query->whereBetween('rank', [501, 1500]);
            } elseif ($selectedRank === "1501-20,000") {
                $query->whereBetween('rank', [1501, 20000]);
            }
        }

        // Sorting
        $selectedSort = $request->get('sort', 'rank_asc');

        switch ($selectedSort) {
            case 'rank_desc':
                $query->orderBy('rank', 'desc');
                break;

            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;

            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;

            case 'rank_asc':
            default:
                $query->orderBy('rank', 'asc');
                break;
        }

        // Pagination
        $universities = $query->paginate(9)->appends($request->query());

        // Country list
        $countries = University::select('country')
            ->distinct()
            ->orderBy('country')
            ->pluck('country');

        // Rank list
        $ranks = ["Top 100", "101-300", "301-500", "501-1500", "1501-20,000"];

        // City list based on selected country
        if ($request->filled('country')) {
            $specificCities = University::select('city')
                ->where('country', $selectedCountry)
                ->distinct()
                ->orderBy('city')
                ->pluck('city');
        } else {
            $specificCities = collect();
        }

        if ($request->ajax()) {
    return response()->json([
        'table' => view('UniversityPages.partials.universityTable', [
            'universities' => $universities,
            'selectedSort' => $selectedSort,
        ])->render(),

        'citySelect' => view('UniversityPages.partials.citySelect', [
            'specificCities' => $specificCities,
            'selectedCity' => $selectedCity,
            'selectedCountry' => $selectedCountry,
        ])->render(),
    ]);
}

        return view('UniversityPages.universities', [
            'universities'    => $universities,
            'countries'       => $countries,
            'specificCities'  => $specificCities,
            'selectedCountry' => $selectedCountry,
            'selectedCity'    => $selectedCity,
            'selectedRank'    => $selectedRank,
            'selectedSort'    => $selectedSort,
            'ranks'           => $ranks,
            'search'          => $request->search,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    
public function show(string $id)
{
    $university = University::findOrFail($id);

    $basePrograms = Program::with(['category', 'requirement', 'languages'])
        ->where('university_id', $id);

    $programs = (clone $basePrograms)
        ->orderBy('name')
        ->paginate(10);

    $categories = Category::select('name')
        ->orderBy('name')
        ->pluck('name');

    $levels = ['Bachelor', 'Master'];

    $intensities = ['Full Time', 'Part Time'];

    $modes = ['On Campus', 'Hybrid', 'Online'];

    $languages = Language::select('name')
        ->orderBy('name')
        ->pluck('name');

    $favoriteProgramIds = collect();

    if (Auth::check()) {
        $student = Student::where('user_id', Auth::id())->first();

        if ($student) {
            $favoriteProgramIds = DB::table('favorites')
                ->where('student_id', $student->id)
                ->pluck('program_id');
        }
    }

    return view('UniversityPages.singleUniversity', [
        'university'         => $university,
        'programs'           => $programs,
        'categories'         => $categories,
        'levels'             => $levels,
        'intensities'        => $intensities,
        'modes'              => $modes,
        'languages'          => $languages,
        'favoriteProgramIds' => $favoriteProgramIds,
    ]);
}
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
