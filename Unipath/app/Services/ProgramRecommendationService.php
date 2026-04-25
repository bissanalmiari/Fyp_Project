<?php

namespace App\Services;

use App\Models\Program;
use App\Models\Recommendation;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

class ProgramRecommendationService
{
    public const COOLDOWN_DAYS = 3;
    private const PROFILE_VERSION = 2;

    public function preferenceHash(Student $student): string
    {
        $student->loadMissing('categories', 'subcategories');

        $payload = [
            'profile_version' => self::PROFILE_VERSION,
            'major' => $student->major,
            'academic_level' => $student->academic_level,
            'gpa' => $student->gpa,
            'ielts' => $student->ielts,
            'toefl' => $student->toefl,
            'sat' => $student->sat,
            'preferred_location' => $student->preferred_location,
            'preferred_study_mode' => $student->preferred_study_mode,
            'preferred_course_intensity' => $student->preferred_course_intensity,
            'budget' => $student->budget,
            'categories' => $student->categories->pluck('name')->sort()->values()->all(),
            'subcategories' => $student->subcategories->pluck('name')->sort()->values()->all(),
            'favorites' => $student->favorites()->pluck('programs.id')->sort()->values()->all(),
        ];

        return hash('sha256', json_encode($payload));
    }

    public function latestRecommendations(Student $student, ?string $hash = null): Collection
    {
        $hash ??= $this->preferenceHash($student);

        return Recommendation::with(['program.university'])
            ->where('student_id', $student->id)
            ->where('preference_hash', $hash)
            ->orderByDesc('created_at')
            ->orderBy('rank')
            ->get()
            ->unique('rank')
            ->sortBy('rank')
            ->values();
    }

    public function recommendationHistory(Student $student, ?string $currentHash = null): Collection
    {
        $currentHash ??= $this->preferenceHash($student);

        return Recommendation::with(['program.university'])
            ->where('student_id', $student->id)
            ->orderByDesc('created_at')
            ->orderBy('rank')
            ->get()
            ->groupBy('preference_hash')
            ->map(function (Collection $items, $hash) use ($currentHash) {
                $ordered = $items->sortBy('rank')->values();

                return [
                    'hash' => $hash,
                    'is_current' => $hash === $currentHash,
                    'generated_at' => $ordered->max('created_at'),
                    'items' => $ordered,
                ];
            })
            ->sortByDesc('generated_at')
            ->values();
    }

    public function canGenerate(Student $student, ?string $hash = null): bool
    {
        $hash ??= $this->preferenceHash($student);

        $latest = Recommendation::where('student_id', $student->id)
            ->where('preference_hash', $hash)
            ->latest()
            ->first();

        return ! $latest || $latest->created_at->lte(now()->subDays(self::COOLDOWN_DAYS));
    }

    public function nextAvailableAt(Student $student, ?string $hash = null): ?Carbon
    {
        $hash ??= $this->preferenceHash($student);

        $latest = Recommendation::where('student_id', $student->id)
            ->where('preference_hash', $hash)
            ->latest()
            ->first();

        if (! $latest) {
            return null;
        }

        return $latest->created_at->copy()->addDays(self::COOLDOWN_DAYS);
    }

    public function generate(Student $student): Collection
    {
        $hash = $this->preferenceHash($student);

        if (! $this->canGenerate($student, $hash)) {
            return $this->latestRecommendations($student, $hash);
        }

        Recommendation::where('student_id', $student->id)
            ->where('preference_hash', $hash)
            ->delete();

        foreach ($this->generationAttempts() as $attempt) {
            $items = $this->runPythonRecommender($student, $attempt);
            $savedCount = $this->storeRecommendations($student, $items, $hash, (bool) $attempt['filter_major']);

            if ($this->latestRecommendations($student, $hash)->count() >= 3) {
                break;
            }
        }

        if ($this->latestRecommendations($student, $hash)->isEmpty()) {
            $this->storeDatabaseFallbackRecommendations($student, $hash);
        }

        return $this->latestRecommendations($student, $hash);
    }

    private function generationAttempts(): array
    {
        return [
            [
                'filter_major' => true,
                'use_major_profile' => true,
                'relax_budget' => false,
                'relax_location' => false,
                'relax_mode' => false,
                'relax_intensity' => false,
            ],
            [
                'filter_major' => true,
                'use_major_profile' => true,
                'relax_budget' => true,
                'relax_location' => false,
                'relax_mode' => false,
                'relax_intensity' => false,
            ],
            [
                'filter_major' => true,
                'use_major_profile' => true,
                'relax_budget' => true,
                'relax_location' => true,
                'relax_mode' => false,
                'relax_intensity' => false,
            ],
            [
                'filter_major' => true,
                'use_major_profile' => true,
                'relax_budget' => true,
                'relax_location' => true,
                'relax_mode' => true,
                'relax_intensity' => true,
            ],
            [
                'filter_major' => false,
                'use_major_profile' => false,
                'relax_budget' => true,
                'relax_location' => true,
                'relax_mode' => true,
                'relax_intensity' => true,
            ],
        ];
    }

    private function storeRecommendations(Student $student, array $items, string $hash, bool $filterMajor): int
    {
        $savedCount = 0;
        $existingProgramIds = Recommendation::where('student_id', $student->id)
            ->where('preference_hash', $hash)
            ->pluck('program_id')
            ->all();
        $rank = count($existingProgramIds) + 1;

        foreach ($items as $item) {
            $program = $this->findProgram($item);

            if (! $program) {
                continue;
            }

            if (in_array($program->id, $existingProgramIds, true)) {
                continue;
            }

            if ($filterMajor && ! $this->matchesMajorProfile($program, $student)) {
                continue;
            }

            Recommendation::create([
                'student_id' => $student->id,
                'program_id' => $program->id,
                'score' => min(100, (int) round(((float) ($item['score'] ?? 0)) * 100)),
                'rank' => $rank,
                'explanation' => json_encode([
                    'summary' => $item['summary'] ?? '',
                    'details' => $item['explanation'] ?? [],
                    'university' => $item['university'] ?? '',
                    'country' => $item['country'] ?? '',
                ]),
                'preference_hash' => $hash,
            ]);

            $savedCount++;
            $existingProgramIds[] = $program->id;
            $rank++;

            if ($rank > 3) {
                break;
            }
        }

        return $savedCount;
    }

    private function runPythonRecommender(Student $student, array $options = []): array
    {
        $payloadPath = storage_path('app/recommendation-student-' . $student->id . '-' . Str::random(8) . '.json');
        $favoritesPath = storage_path('app/recommendation-favorites-' . $student->id . '-' . Str::random(8) . '.csv');
        $feedbackPath = storage_path('app/recommendation-feedback-' . $student->id . '-' . Str::random(8) . '.csv');
        File::put($payloadPath, json_encode($this->studentPayload($student, $options)));
        File::put($favoritesPath, $this->favoritesCsv($student));
        File::put($feedbackPath, $this->feedbackCsv($student));

        try {
            $process = new Process(['python', 'recommend_for_student.py', $payloadPath, $favoritesPath, $feedbackPath], base_path('mm recom'));
            $process->setTimeout(120);
            $process->run();
            $output = $process->getOutput();
        } finally {
            File::delete($payloadPath);
            File::delete($favoritesPath);
            File::delete($feedbackPath);
        }

        $decoded = json_decode((string) $output, true);

        return is_array($decoded) ? $decoded : [];
    }

    private function favoritesCsv(Student $student): string
    {
        $rows = ["student_id,program_name,uni_name"];
        $student->loadMissing('favorites.university');

        foreach ($student->favorites as $program) {
            $rows[] = $this->csvRow([
                $student->id,
                $program->name,
                optional($program->university)->name,
            ]);
        }

        return implode(PHP_EOL, $rows) . PHP_EOL;
    }

    private function feedbackCsv(Student $student): string
    {
        $rows = ["student_id,program_name,uni_name,rating,is_relevant"];

        $feedbacks = \App\Models\FeedbackRecommendation::with('recommendation.program.university')
            ->whereHas('recommendation', fn ($query) => $query->where('student_id', $student->id))
            ->get();

        foreach ($feedbacks as $feedback) {
            $program = optional($feedback->recommendation)->program;

            if (! $program) {
                continue;
            }

            $rows[] = $this->csvRow([
                $student->id,
                $program->name,
                optional($program->university)->name,
                $feedback->rating,
                $feedback->is_relevant ? 1 : 0,
            ]);
        }

        return implode(PHP_EOL, $rows) . PHP_EOL;
    }

    private function csvRow(array $values): string
    {
        return collect($values)
            ->map(function ($value) {
                $value = str_replace('"', '""', (string) $value);

                return '"' . $value . '"';
            })
            ->implode(',');
    }

    private function studentPayload(Student $student, array $options = []): array
    {
        $student->loadMissing('categories', 'subcategories');
        [$budgetMin, $budgetMax] = $this->budgetRange($student->budget);
        $majorProfile = $this->majorProfile($student->major);
        $useMajorProfile = $options['use_major_profile'] ?? true;
        $hasMajorProfile = $useMajorProfile
            && (! empty($majorProfile['broad']) || ! empty($majorProfile['detailed']));

        $broadCategories = collect($hasMajorProfile ? $majorProfile['broad'] : $student->categories->pluck('name'))
            ->filter()
            ->unique()
            ->implode(';');
        $detailedCategories = collect($hasMajorProfile ? $majorProfile['detailed'] : $student->subcategories->pluck('name'))
            ->filter()
            ->unique()
            ->implode(';');

        if ($options['relax_budget'] ?? false) {
            $budgetMin = 0;
            $budgetMax = 999999;
        }

        return [
            'student_id' => $student->id,
            'academic_level' => $student->academic_level,
            'major' => $student->major,
            'gpa' => $student->gpa,
            'ielts' => $student->ielts,
            'toefl' => $student->toefl,
            'sat' => $student->sat,
            'preferred_location' => ($options['relax_location'] ?? false) ? '' : $student->preferred_location,
            'study_mode' => ($options['relax_mode'] ?? false) ? '' : $student->preferred_study_mode,
            'intensity' => ($options['relax_intensity'] ?? false) ? '' : $student->preferred_course_intensity,
            'budget_min' => $budgetMin,
            'budget_max' => $budgetMax,
            'broad_categories_interest' => $broadCategories,
            'detailed_categories_interest' => $detailedCategories,
        ];
    }

    private function majorProfile(?string $major): array
    {
        $major = Str::lower((string) $major);

        $profiles = [
            'computer science' => ['Computing & Technology', 'Computer Science, Software & Information Systems'],
            'software' => ['Computing & Technology', 'Computer Science, Software & Information Systems'],
            'information technology' => ['Computing & Technology', 'Computer Science, Software & Information Systems'],
            'computer engineering' => ['Computing & Technology', 'Computer Engineering, Embedded Systems & Robotics'],
            'cyber' => ['Computing & Technology', 'Cybersecurity, Networks & Cloud Computing'],
            'data science' => ['Data Science & Artificial Intelligence', 'Data Science, Artificial Intelligence & Machine Learning'],
            'artificial intelligence' => ['Data Science & Artificial Intelligence', 'Data Science, Artificial Intelligence & Machine Learning'],
            'business' => ['Business & Management', 'Business Administration, Management & Entrepreneurship'],
            'finance' => ['Business & Management', 'Finance, Accounting, Banking & Fintech'],
            'marketing' => ['Business & Management', 'Marketing, Supply Chain, Logistics & Operations'],
            'psychology' => ['Psychology & Behavioral Sciences', 'Psychology, Counseling & Mental Health'],
            'education' => ['Education', 'Education, Teaching & Curriculum Studies'],
            'mechanical' => ['Engineering', 'Mechanical, Manufacturing & Industrial Engineering'],
            'civil' => ['Engineering', 'Civil, Structural & Construction Engineering'],
            'electrical' => ['Engineering', 'Electrical, Electronic & Communications Engineering'],
            'nursing' => ['Health & Medicine', 'Nursing, Midwifery & Patient Care'],
            'medicine' => ['Health & Medicine', 'Medicine, Dentistry & Clinical Medicine'],
            'architecture' => ['Arts, Design & Architecture', 'Architecture, Urban Planning & Built Environment'],
            'graphic' => ['Arts, Design & Architecture', 'Graphic, Interior, Industrial & Fashion Design'],
        ];

        foreach ($profiles as $needle => [$broad, $detailed]) {
            if (str_contains($major, $needle)) {
                return [
                    'broad' => [$broad],
                    'detailed' => [$detailed],
                ];
            }
        }

        return [
            'broad' => [],
            'detailed' => [],
        ];
    }

    private function budgetRange(?string $budget): array
    {
        return match ($budget) {
            '0_1000' => [0, 1000],
            '1000_4000' => [1000, 4000],
            '4000_8000' => [4000, 8000],
            '8000_plus' => [8000, 999999],
            default => [0, 999999],
        };
    }

    private function findProgram(array $item): ?Program
    {
        $programName = trim((string) ($item['program_name'] ?? ''));
        $universityName = trim((string) ($item['university'] ?? ''));

        if ($programName === '') {
            return null;
        }

        return Program::with('university')
            ->whereRaw('LOWER(name) = ?', [Str::lower($programName)])
            ->when($universityName !== '', function ($query) use ($universityName) {
                $query->whereHas('university', function ($universityQuery) use ($universityName) {
                    $universityQuery->whereRaw('LOWER(name) = ?', [Str::lower($universityName)]);
                });
            })
            ->first()
            ?? Program::whereRaw('LOWER(name) = ?', [Str::lower($programName)])->first();
    }

    private function matchesMajorProfile(Program $program, Student $student): bool
    {
        $majorProfile = $this->majorProfile($student->major);

        $student->loadMissing('categories', 'subcategories');
        $selectedBroad = $student->categories->pluck('name')->all();
        $selectedDetailed = $student->subcategories->pluck('name')->all();
        $hasMajorProfile = ! empty($majorProfile['broad']) || ! empty($majorProfile['detailed']);

        if (
            empty($majorProfile['broad'])
            && empty($majorProfile['detailed'])
            && empty($selectedBroad)
            && empty($selectedDetailed)
        ) {
            return true;
        }

        $program->loadMissing('category', 'subcategories');
        $programBroad = Str::lower((string) optional($program->category)->name);
        $programDetailed = Str::lower((string) optional($program->subcategories)->name);

        $allowedBroad = collect($hasMajorProfile ? $majorProfile['broad'] : $selectedBroad)
            ->map(fn ($item) => Str::lower(trim((string) $item)))
            ->filter()
            ->all();
        $allowedDetailed = collect($hasMajorProfile ? $majorProfile['detailed'] : $selectedDetailed)
            ->map(fn ($item) => Str::lower(trim((string) $item)))
            ->filter()
            ->all();

        return in_array($programBroad, $allowedBroad, true)
            || in_array($programDetailed, $allowedDetailed, true);
    }

    private function storeDatabaseFallbackRecommendations(Student $student, string $hash): int
    {
        $student->loadMissing('categories', 'subcategories');
        $majorProfile = $this->majorProfile($student->major);
        $hasMajorProfile = ! empty($majorProfile['broad']) || ! empty($majorProfile['detailed']);
        $studentBroad = collect($hasMajorProfile ? $majorProfile['broad'] : $student->categories->pluck('name'))
            ->map(fn ($item) => Str::lower(trim((string) $item)))
            ->filter()
            ->unique()
            ->values();
        $studentDetailed = collect($hasMajorProfile ? $majorProfile['detailed'] : $student->subcategories->pluck('name'))
            ->map(fn ($item) => Str::lower(trim((string) $item)))
            ->filter()
            ->unique()
            ->values();
        [$budgetMin, $budgetMax] = $this->budgetRange($student->budget);

        $ranked = Program::with(['university', 'category', 'subcategories', 'requirement'])
            ->get()
            ->map(function (Program $program) use ($student, $studentBroad, $studentDetailed, $budgetMin, $budgetMax) {
                return [
                    'program' => $program,
                    'score' => $this->fallbackScore($program, $student, $studentBroad, $studentDetailed, $budgetMin, $budgetMax),
                ];
            })
            ->filter(fn ($item) => $item['score']['points'] > 0)
            ->sortByDesc(fn ($item) => $item['score']['points'])
            ->take(3)
            ->values();

        $savedCount = 0;

        foreach ($ranked as $index => $item) {
            /** @var Program $program */
            $program = $item['program'];
            $score = $item['score'];

            Recommendation::create([
                'student_id' => $student->id,
                'program_id' => $program->id,
                'score' => min(100, max(1, (int) $score['points'])),
                'rank' => $index + 1,
                'explanation' => json_encode([
                    'summary' => $this->fallbackSummary($program, $score['reasons']),
                    'details' => $score['reasons'],
                    'university' => optional($program->university)->name,
                    'country' => optional($program->university)->country,
                ]),
                'preference_hash' => $hash,
            ]);

            $savedCount++;
        }

        return $savedCount;
    }

    private function fallbackScore(Program $program, Student $student, Collection $studentBroad, Collection $studentDetailed, float $budgetMin, float $budgetMax): array
    {
        $points = 0;
        $reasons = [];
        $programBroad = Str::lower((string) optional($program->category)->name);
        $programDetailed = Str::lower((string) optional($program->subcategories)->name);
        $country = Str::lower((string) optional($program->university)->country);
        $preferredCountry = Str::lower((string) $student->preferred_location);
        $studyMode = $this->normalizeMode($program->study_mode);
        $preferredMode = $this->normalizeMode($student->preferred_study_mode);
        $intensity = $this->normalizeIntensity($program->course_intensity);
        $preferredIntensity = $this->normalizeIntensity($student->preferred_course_intensity);
        $tuition = $this->bestTuitionForStudent($program, $student);

        if ($studentDetailed->contains($programDetailed)) {
            $points += 45;
            $reasons[] = 'Matches your selected skill or major specialization';
        }

        if ($studentBroad->contains($programBroad)) {
            $points += 35;
            $reasons[] = 'Matches your academic interest area';
        }

        if ($preferredCountry !== '' && $country === $preferredCountry) {
            $points += 15;
            $reasons[] = 'Matches your preferred country';
        }

        if ($preferredMode !== '' && $studyMode === $preferredMode) {
            $points += 8;
            $reasons[] = 'Matches your preferred study mode';
        }

        if ($preferredIntensity !== '' && $intensity === $preferredIntensity) {
            $points += 8;
            $reasons[] = 'Matches your preferred course intensity';
        }

        if ($tuition !== null && $tuition >= $budgetMin && $tuition <= $budgetMax) {
            $points += 12;
            $reasons[] = 'Fits your budget range';
        }

        if ($student->academic_level && $program->level) {
            $studentLevel = Str::lower($student->academic_level);
            $programLevel = Str::lower($program->level);

            if (($studentLevel === 'high school' && $programLevel === 'bachelor')
                || ($studentLevel === 'undergraduate' && $programLevel === 'master')) {
                $points += 12;
                $reasons[] = 'Fits your next academic level';
            }
        }

        foreach ($this->academicRequirementReasons($program, $student) as $reason) {
            $reasons[] = $reason;
        }

        if (empty($reasons)) {
            $reasons[] = 'Closest available match based on your saved profile';
        }

        return [
            'points' => $points,
            'reasons' => array_values(array_unique($reasons)),
        ];
    }

    private function fallbackSummary(Program $program, array $reasons): string
    {
        $reason = $reasons[0] ?? 'matches your saved profile';

        return "{$program->name} is recommended because it {$this->summaryReason($reason)}.";
    }

    private function summaryReason(string $reason): string
    {
        return match ($reason) {
            'Matches your selected skill or major specialization' => 'matches your selected skill or major specialization',
            'Matches your academic interest area' => 'matches your academic interest area',
            'Matches your preferred country' => 'matches your preferred country',
            'Matches your preferred study mode' => 'matches your preferred study mode',
            'Matches your preferred course intensity' => 'matches your preferred course intensity',
            'Fits your budget range' => 'fits your budget range',
            'Fits your next academic level' => 'fits your next academic level',
            default => 'is the closest available match for your saved profile',
        };
    }

    private function normalizeMode(?string $value): string
    {
        $value = Str::lower(trim((string) $value));

        return match ($value) {
            'on-campus', 'on campus', 'oncampus' => 'on campus',
            'online' => 'online',
            'hybrid', 'blended' => 'hybrid',
            default => $value,
        };
    }

    private function normalizeIntensity(?string $value): string
    {
        $value = Str::lower(trim((string) $value));

        return match ($value) {
            'full-time', 'full time' => 'full time',
            'part-time', 'part time' => 'part time',
            default => $value,
        };
    }

    private function bestTuitionForStudent(Program $program, Student $student): ?float
    {
        $nationality = Str::lower((string) $student->nationality);
        $country = Str::lower((string) $student->country);

        $preferredColumns = match (true) {
            str_contains($nationality, 'leban') || str_contains($country, 'leban') => ['leb_fees'],
            str_contains($nationality, 'palestin') => ['pal_fees'],
            str_contains($nationality, 'arab') => ['arab_fees'],
            str_contains($nationality, 'us') || str_contains($nationality, 'american') => ['us_fees'],
            default => [],
        };

        $columns = array_merge($preferredColumns, ['leb_fees', 'arab_fees', 'pal_fees', 'us_fees', 'eu_fees', 'non_eu_fees']);

        foreach (array_unique($columns) as $column) {
            $value = $program->{$column};

            if ($value !== null) {
                return (float) $value;
            }
        }

        return null;
    }

    private function academicRequirementReasons(Program $program, Student $student): array
    {
        $program->loadMissing('requirement');
        $requirement = $program->requirement;

        if (! $requirement) {
            return [];
        }

        $reasons = [];
        $checks = [
            'GPA' => [(float) $student->gpa, $requirement->minimum_gpa],
            'IELTS' => [(float) $student->ielts, $requirement->ielts],
            'TOEFL' => [(float) $student->toefl, $requirement->toefl],
            'SAT' => [(float) $student->sat, $requirement->sat],
        ];

        foreach ($checks as $label => [$studentScore, $requiredScore]) {
            if ($requiredScore === null) {
                continue;
            }

            $requiredScore = (float) $requiredScore;

            if ($studentScore >= $requiredScore) {
                $reasons[] = "Your {$label} score meets the requirement ({$studentScore} vs {$requiredScore})";
            } else {
                $reasons[] = "Your {$label} score is below the requirement ({$studentScore} vs {$requiredScore})";
            }
        }

        return $reasons;
    }
}
