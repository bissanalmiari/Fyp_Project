<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Student;
use App\Models\University;
use App\Services\ProgramComparisonService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ComparisonController extends Controller
{
    public function index(Request $request, ProgramComparisonService $comparisonService)
    {
        $universities = University::orderBy('name')->get();
        $criteriaBlueprint = ProgramComparisonService::blueprint();

        $selectedUniversityAId = $request->university_a_id;
        $selectedUniversityBId = $request->university_b_id;
        $selectedProgramAId = $request->program_a_id;
        $selectedProgramBId = $request->program_b_id;

        $selectedUniversityA = $selectedUniversityAId ? University::find($selectedUniversityAId) : null;
        $selectedUniversityB = $selectedUniversityBId ? University::find($selectedUniversityBId) : null;

        $programsA = $selectedUniversityA
            ? Program::where('university_id', $selectedUniversityA->id)->orderBy('name')->get()
            : collect();

        $programsB = $selectedUniversityB
            ? Program::where('university_id', $selectedUniversityB->id)->orderBy('name')->get()
            : collect();

        $selectedProgramA = null;
        $selectedProgramB = null;

        if ($selectedProgramAId && $selectedUniversityA) {
            $selectedProgramA = Program::with([
                'category',
                'subcategory',
                'university',
                'languages',
                'requirement',
            ])
                ->where('university_id', $selectedUniversityA->id)
                ->whereKey($selectedProgramAId)
                ->first();
        }

        if ($selectedProgramBId && $selectedUniversityB) {
            $selectedProgramB = Program::with([
                'category',
                'subcategory',
                'university',
                'languages',
                'requirement',
            ])
                ->where('university_id', $selectedUniversityB->id)
                ->whereKey($selectedProgramBId)
                ->first();
        }

        $student = auth()->check()
            ? Student::with(['categories', 'subcategories'])->where('user_id', auth()->id())->first()
            : null;

        $selectedProgramATuition = ($student && $selectedProgramA)
            ? $comparisonService->formatEffectiveTuitionForStudent($student, $selectedProgramA)
            : 'N/A';

        $selectedProgramBTuition = ($student && $selectedProgramB)
            ? $comparisonService->formatEffectiveTuitionForStudent($student, $selectedProgramB)
            : 'N/A';

        $state = [
            'selectedUniversityA' => $selectedUniversityA,
            'selectedUniversityB' => $selectedUniversityB,
            'selectedUniversityAId' => $selectedUniversityAId,
            'selectedUniversityBId' => $selectedUniversityBId,
            'programsA' => $programsA,
            'programsB' => $programsB,
            'selectedProgramA' => $selectedProgramA,
            'selectedProgramB' => $selectedProgramB,
            'selectedProgramAId' => $selectedProgramAId,
            'selectedProgramBId' => $selectedProgramBId,
            'selectedProgramATuition' => $selectedProgramATuition,
            'selectedProgramBTuition' => $selectedProgramBTuition,
            'criteriaBlueprint' => $criteriaBlueprint,
        ];

        if ($request->ajax()) {
            return response()->json([
                'programFilters' => view('ComparisonPages.partials.programSelectors', $state)->render(),
                'selectedCards' => view('ComparisonPages.partials.selectedProgramCards', array_merge($state, [
                    'comparisonData' => null,
                ]))->render(),
                'comparisonSection' => view('ComparisonPages.partials.comparisonSection', array_merge($state, [
                    'comparisonData' => null,
                    'isCompared' => false,
                ]))->render(),
            ]);
        }

        return view('ComparisonPages.comparePrograms', array_merge([
            'universities' => $universities,
        ], $state, [
            'comparisonData' => null,
            'isCompared' => false,
        ]));
    }

    public function compare(Request $request, ProgramComparisonService $comparisonService)
    {
        $request->validate([
            'program_a_id' => ['required', 'exists:programs,id'],
            'program_b_id' => ['required', 'exists:programs,id'],
        ]);

        $student = Student::with(['categories', 'subcategories'])
            ->where('user_id', auth()->id())
            ->first();

        if (!$student) {
            return response()->json([
                'message' => 'Student profile not found.'
            ], 403);
        }

        $programA = Program::with([
            'category',
            'subcategory',
            'university',
            'languages',
            'requirement',
        ])->findOrFail($request->program_a_id);

        $programB = Program::with([
            'category',
            'subcategory',
            'university',
            'languages',
            'requirement',
        ])->findOrFail($request->program_b_id);

        $comparisonData = $comparisonService->compare($student, $programA, $programB);

        return response()->json([
            'selectedCards' => view('ComparisonPages.partials.selectedProgramCards', [
                'selectedProgramA' => $programA,
                'selectedProgramB' => $programB,
                'comparisonData' => $comparisonData,
                'selectedProgramATuition' => $comparisonService->formatEffectiveTuitionForStudent($student, $programA),
                'selectedProgramBTuition' => $comparisonService->formatEffectiveTuitionForStudent($student, $programB),
            ])->render(),
            'comparisonSection' => view('ComparisonPages.partials.comparisonSection', [
                'selectedProgramA' => $programA,
                'selectedProgramB' => $programB,
                'criteriaBlueprint' => ProgramComparisonService::blueprint(),
                'comparisonData' => $comparisonData,
                'isCompared' => true,
            ])->render(),
        ]);
    }

    public function export(Request $request, ProgramComparisonService $comparisonService)
    {
        $request->validate([
            'program_a_id' => ['required', 'exists:programs,id'],
            'program_b_id' => ['required', 'exists:programs,id'],
        ]);

        $student = Student::with(['categories', 'subcategories'])
            ->where('user_id', auth()->id())
            ->first();

        if (!$student) {
            abort(403, 'Student profile not found.');
        }

        $programA = Program::with([
            'category',
            'subcategory',
            'university',
            'languages',
            'requirement',
        ])->findOrFail($request->program_a_id);

        $programB = Program::with([
            'category',
            'subcategory',
            'university',
            'languages',
            'requirement',
        ])->findOrFail($request->program_b_id);

        $comparisonData = $comparisonService->compare($student, $programA, $programB);

        $pdf = Pdf::loadView('ComparisonPages.pdf.comparisonExport', [
            'programA' => $programA,
            'programB' => $programB,
            'comparisonData' => $comparisonData,
            'selectedProgramATuition' => $comparisonService->formatEffectiveTuitionForStudent($student, $programA),
            'selectedProgramBTuition' => $comparisonService->formatEffectiveTuitionForStudent($student, $programB),
        ])->setPaper('a4', 'portrait');

        return $pdf->download('program-comparison.pdf');
    }
}