<?php

namespace App\Services;

use App\Models\Program;
use App\Models\Student;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ProgramComparisonService
{
    public static function blueprint(): array
    {
        return [
            'academic_level'    => ['label' => 'Academic Level Match', 'weight' => 10, 'group' => 'academic'],
            'gpa'               => ['label' => 'GPA Requirement', 'weight' => 10, 'group' => 'academic'],
            'sat'               => ['label' => 'SAT Requirement', 'weight' => 5, 'group' => 'academic'],
            'english'           => ['label' => 'English Requirement', 'weight' => 10, 'group' => 'academic'],
            'tuition'           => ['label' => 'Tuition Compatibility', 'weight' => 10, 'group' => 'cost'],
            'location'          => ['label' => 'Preferred Location', 'weight' => 10, 'group' => 'preferences'],
            'study_mode'        => ['label' => 'Study Mode Match', 'weight' => 10, 'group' => 'preferences'],
            'course_intensity'  => ['label' => 'Course Intensity Match', 'weight' => 5, 'group' => 'preferences'],
            'category_match'    => ['label' => 'Category Match', 'weight' => 10, 'group' => 'relevance'],
            'subcategory_match' => ['label' => 'Sub Category Match', 'weight' => 10, 'group' => 'relevance'],
            'major_relevance'   => ['label' => 'Major Relevance', 'weight' => 10, 'group' => 'relevance'],
        ];
    }

    protected array $euCountries = [
        'austria', 'belgium', 'bulgaria', 'croatia', 'cyprus', 'czech republic',
        'denmark', 'estonia', 'finland', 'france', 'germany', 'greece', 'hungary',
        'ireland', 'italy', 'latvia', 'lithuania', 'luxembourg', 'malta',
        'netherlands', 'poland', 'portugal', 'romania', 'slovakia', 'slovenia',
        'spain', 'sweden'
    ];

    protected array $categoryRelations = [
        'Engineering' => [
            'Computing & Technology' => 0.8,
            'Data Science & Artificial Intelligence' => 0.5,
            'Natural & Physical Sciences' => 0.4,
            'Transport, Aviation & Maritime' => 0.5,
            'Interdisciplinary & Emerging Fields' => 0.4,
        ],
        'Computing & Technology' => [
            'Engineering' => 0.8,
            'Data Science & Artificial Intelligence' => 0.9,
            'Business & Management' => 0.3,
            'Interdisciplinary & Emerging Fields' => 0.5,
        ],
        'Data Science & Artificial Intelligence' => [
            'Computing & Technology' => 0.9,
            'Natural & Physical Sciences' => 0.6,
            'Business & Management' => 0.5,
            'Interdisciplinary & Emerging Fields' => 0.5,
        ],
        'Health & Medicine' => [
            'Life Sciences' => 0.8,
            'Psychology & Behavioral Sciences' => 0.4,
            'Natural & Physical Sciences' => 0.3,
        ],
        'Life Sciences' => [
            'Health & Medicine' => 0.8,
            'Natural & Physical Sciences' => 0.6,
            'Environment & Agriculture' => 0.4,
        ],
        'Natural & Physical Sciences' => [
            'Engineering' => 0.4,
            'Data Science & Artificial Intelligence' => 0.6,
            'Life Sciences' => 0.6,
            'Environment & Agriculture' => 0.5,
        ],
        'Social Sciences' => [
            'Business & Management' => 0.4,
            'Law & Governance' => 0.5,
            'Humanities & Languages' => 0.4,
            'Psychology & Behavioral Sciences' => 0.6,
        ],
        'Business & Management' => [
            'Social Sciences' => 0.4,
            'Law & Governance' => 0.3,
            'Hospitality & Tourism' => 0.6,
            'Data Science & Artificial Intelligence' => 0.5,
        ],
        'Law & Governance' => [
            'Social Sciences' => 0.5,
            'Business & Management' => 0.3,
            'Humanities & Languages' => 0.3,
        ],
        'Education' => [
            'Humanities & Languages' => 0.4,
            'Social Sciences' => 0.4,
            'Psychology & Behavioral Sciences' => 0.4,
        ],
        'Arts, Design & Architecture' => [
            'Media & Communication' => 0.7,
            'Humanities & Languages' => 0.3,
        ],
        'Humanities & Languages' => [
            'Social Sciences' => 0.4,
            'Education' => 0.4,
            'Arts, Design & Architecture' => 0.3,
            'Law & Governance' => 0.3,
            'Media & Communication' => 0.3,
        ],
        'Environment & Agriculture' => [
            'Natural & Physical Sciences' => 0.5,
            'Life Sciences' => 0.4,
            'Engineering' => 0.3,
        ],
        'Media & Communication' => [
            'Arts, Design & Architecture' => 0.7,
            'Social Sciences' => 0.4,
            'Business & Management' => 0.3,
            'Humanities & Languages' => 0.3,
        ],
        'Hospitality & Tourism' => [
            'Business & Management' => 0.6,
            'Sports & Events' => 0.3,
            'Transport, Aviation & Maritime' => 0.3,
        ],
        'Sports & Events' => [
            'Health & Medicine' => 0.4,
            'Hospitality & Tourism' => 0.3,
            'Psychology & Behavioral Sciences' => 0.3,
        ],
        'Transport, Aviation & Maritime' => [
            'Engineering' => 0.5,
            'Hospitality & Tourism' => 0.3,
        ],
        'Interdisciplinary & Emerging Fields' => [
            'Computing & Technology' => 0.5,
            'Data Science & Artificial Intelligence' => 0.5,
            'Engineering' => 0.4,
            'Business & Management' => 0.3,
        ],
        'Psychology & Behavioral Sciences' => [
            'Social Sciences' => 0.6,
            'Health & Medicine' => 0.4,
            'Education' => 0.4,
        ],
    ];

    protected array $domainKeywords = [
        'engineering' => ['mechanical', 'manufacturing', 'industrial engineering', 'civil', 'structural', 'construction engineering', 'electrical', 'electronic', 'communications engineering', 'telecommunications', 'chemical engineering', 'petroleum', 'materials', 'mechatronics'],
        'computing' => ['computer science', 'software', 'information systems', 'information technology', 'computer engineering', 'embedded systems', 'robotics', 'cybersecurity', 'networks', 'cloud computing', 'programming'],
        'data_ai' => ['data science', 'artificial intelligence', 'machine learning', 'business analytics', 'decision science', 'intelligent systems', 'ai'],
        'health' => ['medicine', 'dentistry', 'clinical medicine', 'nursing', 'midwifery', 'patient care', 'allied health', 'physiotherapy', 'rehabilitation', 'pharmacy', 'pharmacology', 'pharmaceutical', 'public health', 'nutrition', 'health management'],
        'life_sciences' => ['biology', 'biological sciences', 'genetics', 'molecular biology', 'biotechnology', 'biomedical sciences', 'bioinformatics', 'neuroscience', 'microbiology'],
        'natural_sciences' => ['mathematics', 'statistics', 'actuarial science', 'physics', 'astronomy', 'space science', 'chemistry', 'earth sciences', 'geology', 'geophysics'],
        'social_sciences' => ['economics', 'econometrics', 'development studies', 'political science', 'international relations', 'public affairs', 'sociology', 'anthropology', 'human geography'],
        'business' => ['business administration', 'management', 'entrepreneurship', 'finance', 'accounting', 'banking', 'fintech', 'marketing', 'supply chain', 'logistics', 'operations'],
        'law' => ['law', 'legal studies', 'justice', 'governance', 'public policy', 'international law'],
        'education' => ['education', 'teaching', 'curriculum', 'educational leadership', 'special education', 'instruction'],
        'arts' => ['architecture', 'urban planning', 'built environment', 'graphic design', 'interior design', 'industrial design', 'fashion design', 'fine arts', 'visual arts', 'creative practice', 'music', 'performing arts', 'creative technology'],
        'humanities' => ['languages', 'linguistics', 'translation', 'applied languages', 'literature', 'history', 'philosophy', 'religious studies', 'cultural studies', 'area studies', 'heritage'],
        'environment' => ['environmental science', 'sustainability', 'ecology', 'agriculture', 'forestry', 'food science', 'natural resources'],
        'media' => ['media', 'communication', 'journalism', 'public relations', 'film', 'digital media', 'advertising', 'content production'],
        'hospitality' => ['hospitality', 'tourism', 'travel', 'hotel management'],
        'sports' => ['sports science', 'exercise science', 'athletic performance', 'event management', 'recreation', 'leisure studies'],
        'transport' => ['aviation', 'aeronautics', 'aerospace', 'maritime', 'shipping', 'transport systems'],
        'interdisciplinary' => ['interdisciplinary', 'innovation', 'emerging studies'],
        'psychology' => ['psychology', 'counseling', 'mental health', 'cognitive science', 'behavioral science', 'behavioural science'],
    ];

    protected array $domainToCategory = [
        'engineering' => 'Engineering',
        'computing' => 'Computing & Technology',
        'data_ai' => 'Data Science & Artificial Intelligence',
        'health' => 'Health & Medicine',
        'life_sciences' => 'Life Sciences',
        'natural_sciences' => 'Natural & Physical Sciences',
        'social_sciences' => 'Social Sciences',
        'business' => 'Business & Management',
        'law' => 'Law & Governance',
        'education' => 'Education',
        'arts' => 'Arts, Design & Architecture',
        'humanities' => 'Humanities & Languages',
        'environment' => 'Environment & Agriculture',
        'media' => 'Media & Communication',
        'hospitality' => 'Hospitality & Tourism',
        'sports' => 'Sports & Events',
        'transport' => 'Transport, Aviation & Maritime',
        'interdisciplinary' => 'Interdisciplinary & Emerging Fields',
        'psychology' => 'Psychology & Behavioral Sciences',
    ];

    public function getEffectiveTuitionForStudent(Student $student, Program $program): ?float
    {
        return $this->effectiveTuitionForStudent($student, $program);
    }

    public function formatEffectiveTuitionForStudent(Student $student, Program $program): string
    {
        $tuition = $this->effectiveTuitionForStudent($student, $program);

        if (is_null($tuition)) {
            return 'N/A';
        }

        return number_format($tuition, 2);
    }

    public function compare(Student $student, Program $programA, Program $programB): array
    {
        $blueprint = self::blueprint();

        $evaluationA = $this->evaluateProgram($student, $programA);
        $evaluationB = $this->evaluateProgram($student, $programB);

        $rows = [];
        $printedGroups = [];

        foreach ($blueprint as $key => $meta) {
            $a = $evaluationA['criteria'][$key];
            $b = $evaluationB['criteria'][$key];

            $rowWinner = 'Tie';

            if ($a['points'] > $b['points']) {
                $rowWinner = 'Program A';
            } elseif ($b['points'] > $a['points']) {
                $rowWinner = 'Program B';
            }

            $rows[] = [
                'key' => $key,
                'label' => $meta['label'],
                'group' => $meta['group'],
                'group_label' => in_array($meta['group'], $printedGroups, true)
                    ? null
                    : $this->formatGroupLabel($meta['group']),
                'weight' => $meta['weight'],
                'program_a_percent' => $a['percent'],
                'program_b_percent' => $b['percent'],
                'program_a_points' => $a['points'],
                'program_b_points' => $b['points'],
                'program_a_note' => $a['note'],
                'program_b_note' => $b['note'],
                'winner' => $rowWinner,
            ];

            $printedGroups[] = $meta['group'];
        }

        $totalMax = array_sum(array_column($blueprint, 'weight'));

        $overallA = round($evaluationA['total_points'], 1);
        $overallB = round($evaluationB['total_points'], 1);

        $overallPercentA = round(($overallA / $totalMax) * 100, 1);
        $overallPercentB = round(($overallB / $totalMax) * 100, 1);

        $winnerKey = 'Tie';
        $winnerProgram = null;
        $winnerOverall = $overallA;
        $winnerGroups = [];

        if ($overallA > $overallB) {
            $winnerKey = 'A';
            $winnerProgram = $programA;
            $winnerOverall = $overallA;
            $winnerGroups = $evaluationA['groups'];
        } elseif ($overallB > $overallA) {
            $winnerKey = 'B';
            $winnerProgram = $programB;
            $winnerOverall = $overallB;
            $winnerGroups = $evaluationB['groups'];
        } else {
            $winnerGroups = [
                'academic' => round(($evaluationA['groups']['academic'] + $evaluationB['groups']['academic']) / 2, 1),
                'preferences' => round(($evaluationA['groups']['preferences'] + $evaluationB['groups']['preferences']) / 2, 1),
                'relevance' => round(($evaluationA['groups']['relevance'] + $evaluationB['groups']['relevance']) / 2, 1),
                'cost' => round(($evaluationA['groups']['cost'] + $evaluationB['groups']['cost']) / 2, 1),
            ];
        }

        $tiedPrograms = [];

        if ($overallA === $overallB) {
            $tiedPrograms = [
                [
                    'program_name' => $programA->name,
                    'overall' => $overallA,
                    'overall_percent' => $overallPercentA,
                    'groups' => $evaluationA['groups'],
                ],
                [
                    'program_name' => $programB->name,
                    'overall' => $overallB,
                    'overall_percent' => $overallPercentB,
                    'groups' => $evaluationB['groups'],
                ],
            ];
        } elseif ($winnerProgram) {
            $tiedPrograms = [
                [
                    'program_name' => $winnerProgram->name,
                    'overall' => $winnerOverall,
                    'overall_percent' => $winnerKey === 'A' ? $overallPercentA : $overallPercentB,
                    'groups' => $winnerGroups,
                ]
            ];
        }

        return [
            'rows' => $rows,
            'summary_row' => [
                'label' => 'Overall Result',
                'program_a_points' => $overallA,
                'program_b_points' => $overallB,
                'program_a_percent' => $overallPercentA,
                'program_b_percent' => $overallPercentB,
                'winner' => $winnerKey === 'Tie'
                    ? 'Tie'
                    : ($winnerKey === 'A' ? 'Program A' : 'Program B'),
            ],
            'program_a' => [
                'overall' => $overallA,
                'overall_percent' => $overallPercentA,
                'groups' => $evaluationA['groups'],
                'warnings' => $evaluationA['warnings'],
            ],
            'program_b' => [
                'overall' => $overallB,
                'overall_percent' => $overallPercentB,
                'groups' => $evaluationB['groups'],
                'warnings' => $evaluationB['warnings'],
            ],
            'winner' => [
                'key' => $winnerKey,
                'program_name' => $winnerProgram?->name ?? 'Tie between selected programs',
                'overall' => $winnerOverall,
                'groups' => $winnerGroups,
                'tied_programs' => $tiedPrograms,
            ],
        ];
    }

    protected function evaluateProgram(Student $student, Program $program): array
    {
        $criteria = [
            'academic_level'    => $this->scoreAcademicLevel($student, $program),
            'gpa'               => $this->scoreGpa($student, $program),
            'sat'               => $this->scoreSat($student, $program),
            'english'           => $this->scoreEnglish($student, $program),
            'tuition'           => $this->scoreTuition($student, $program),
            'location'          => $this->scoreLocation($student, $program),
            'study_mode'        => $this->scoreStudyMode($student, $program),
            'course_intensity'  => $this->scoreCourseIntensity($student, $program),
            'category_match'    => $this->scoreCategoryMatch($student, $program),
            'subcategory_match' => $this->scoreSubcategoryMatch($student, $program),
            'major_relevance'   => $this->scoreMajorRelevance($student, $program),
        ];

        $blueprint = self::blueprint();

        $totalPoints = 0;

        $groupPoints = [
            'academic' => 0,
            'cost' => 0,
            'preferences' => 0,
            'relevance' => 0,
        ];

        $groupMax = [
            'academic' => 35,
            'cost' => 10,
            'preferences' => 25,
            'relevance' => 30,
        ];

        foreach ($criteria as $key => &$criterion) {
            $weight = $blueprint[$key]['weight'];

            $criterion['max'] = $weight;
            $criterion['percent'] = round(($criterion['points'] / $weight) * 100, 1);

            $totalPoints += $criterion['points'];

            $groupPoints[$blueprint[$key]['group']] += $criterion['points'];
        }

        unset($criterion);

        $groups = [];

        foreach ($groupPoints as $group => $points) {
            $groups[$group] = round(($points / $groupMax[$group]) * 100, 1);
        }

        return [
            'criteria' => $criteria,
            'total_points' => $totalPoints,
            'groups' => $groups,
            'warnings' => $this->buildWarnings($criteria),
        ];
    }

    protected function buildWarnings(array $criteria): array
    {
        $warnings = [];

        if (
            $criteria['category_match']['points'] <= 2 &&
            $criteria['subcategory_match']['points'] <= 3 &&
            $criteria['major_relevance']['points'] <= 4
        ) {
            $warnings[] = 'This program appears weakly related to the student’s selected interests and academic background.';
        }

        if ($criteria['academic_level']['points'] === 0) {
            $warnings[] = 'This program does not match the student’s academic level.';
        }

        return $warnings;
    }

    protected function scoreAcademicLevel(Student $student, Program $program): array
    {
        $studentLevel = Str::lower(trim((string) $student->academic_level));
        $programLevel = Str::lower(trim((string) $program->level));

        if (!$studentLevel || !$programLevel) {
            return ['points' => 0, 'note' => 'Missing academic level'];
        }

        $expected = null;

        if (str_contains($studentLevel, 'high')) {
            $expected = 'bachelor';
        } elseif (str_contains($studentLevel, 'bachelor') || str_contains($studentLevel, 'undergraduate')) {
            $expected = 'master';
        }

        if (!$expected) {
            return ['points' => 0, 'note' => 'Unknown student academic level'];
        }

        return [
            'points' => $programLevel === $expected ? 10 : 0,
            'note' => $programLevel === $expected ? 'Level matches profile' : 'Level does not match profile',
        ];
    }

    protected function scoreGpa(Student $student, Program $program): array
    {
        $required = $program->requirement?->minimum_gpa;
        $studentGpa = $this->normalizeStudentGpa($student->gpa);

        if (!is_null($required) && (float) $required > 10) {
            $required = round(((float) $required / 100) * 4, 2);
        }

        if (is_null($required)) {
            return ['points' => 10, 'note' => 'No GPA requirement'];
        }

        if (is_null($studentGpa)) {
            return ['points' => 0, 'note' => 'Student GPA not available'];
        }

        $difference = (float) $studentGpa - (float) $required;

        if ($difference >= 0) {
            return ['points' => 10, 'note' => 'GPA meets requirement'];
        }

        if ($difference >= -0.2) {
            return ['points' => 5, 'note' => 'GPA is slightly below requirement'];
        }

        return ['points' => 0, 'note' => 'GPA is below requirement'];
    }

    protected function scoreSat(Student $student, Program $program): array
    {
        $required = $program->requirement?->sat;
        $studentSat = $student->sat;

        if (is_null($required)) {
            return ['points' => 5, 'note' => 'No SAT requirement'];
        }

        if (is_null($studentSat)) {
            return ['points' => 0, 'note' => 'Student SAT not available'];
        }

        if ((int) $studentSat >= (int) $required) {
            return ['points' => 5, 'note' => 'SAT meets requirement'];
        }

        if ((int) $studentSat >= ((int) $required - 100)) {
            return ['points' => 2.5, 'note' => 'SAT is slightly below requirement'];
        }

        return ['points' => 0, 'note' => 'SAT is below requirement'];
    }

    protected function scoreEnglish(Student $student, Program $program): array
    {
        $requiredIelts = $program->requirement?->ielts;
        $requiredToefl = $program->requirement?->toefl;

        if (is_null($requiredIelts) && is_null($requiredToefl)) {
            return ['points' => 10, 'note' => 'No English requirement'];
        }

        $studentIelts = $student->ielts;
        $studentToefl = $student->toefl;

        $ieltsPass = !is_null($requiredIelts)
            && !is_null($studentIelts)
            && (float) $studentIelts >= (float) $requiredIelts;

        $toeflPass = !is_null($requiredToefl)
            && !is_null($studentToefl)
            && (float) $studentToefl >= (float) $requiredToefl;

        if ($ieltsPass || $toeflPass) {
            return ['points' => 10, 'note' => 'English requirement satisfied'];
        }

        $ieltsNear = !is_null($requiredIelts)
            && !is_null($studentIelts)
            && (float) $studentIelts >= ((float) $requiredIelts - 0.5);

        $toeflNear = !is_null($requiredToefl)
            && !is_null($studentToefl)
            && (float) $studentToefl >= ((float) $requiredToefl - 10);

        if ($ieltsNear || $toeflNear) {
            return ['points' => 5, 'note' => 'English score is close to requirement'];
        }

        return ['points' => 0, 'note' => 'English requirement not satisfied'];
    }

    protected function scoreTuition(Student $student, Program $program): array
    {
        $tuition = $this->effectiveTuitionForStudent($student, $program);

        if (is_null($tuition)) {
            return ['points' => 0, 'note' => 'Tuition data not available'];
        }

        $bounds = $this->getBudgetBounds($student->budget);

        $min = $bounds['min'];
        $max = $bounds['max'];

        if (is_null($min) && is_null($max)) {
            return ['points' => 0, 'note' => 'Student budget not available'];
        }

        if (!is_null($min) && is_null($max)) {
            if ($tuition >= $min) {
                return ['points' => 10, 'note' => 'Tuition is within preferred budget range'];
            }

            return ['points' => 7, 'note' => 'Tuition is below preferred budget range'];
        }

        if ($tuition >= $min && $tuition <= $max) {
            return ['points' => 10, 'note' => 'Tuition is within preferred budget range'];
        }

        if ($tuition < $min) {
            return ['points' => 8, 'note' => 'Tuition is below preferred budget range'];
        }

        if ($tuition <= ($max * 1.10)) {
            return ['points' => 6, 'note' => 'Tuition is slightly above preferred budget range'];
        }

        if ($tuition <= ($max * 1.25)) {
            return ['points' => 3, 'note' => 'Tuition is moderately above preferred budget range'];
        }

        return ['points' => 0, 'note' => 'Tuition is above preferred budget range'];
    }

    protected function getBudgetBounds(?string $budgetRange): array
    {
        return match ($budgetRange) {
            '0-1000', '0_1000' => ['min' => 0, 'max' => 1000],
            '1000-4000', '1000_4000' => ['min' => 1000, 'max' => 4000],
            '4000-8000', '4000_8000' => ['min' => 4000, 'max' => 8000],
            '8000+', '8000_plus' => ['min' => 8000, 'max' => null],
            default => ['min' => null, 'max' => null],
        };
    }

    protected function scoreLocation(Student $student, Program $program): array
    {
        $preferredLocations = $this->normalizedPreferenceValues($student, 'preferred_location');

        $country = $this->normalizeText($program->university?->country);
        $city = $this->normalizeText($program->university?->city);

        if (empty($preferredLocations)) {
            return ['points' => 0, 'note' => 'No preferred location selected'];
        }

        if (!$country && !$city) {
            return ['points' => 0, 'note' => 'Program location data not available'];
        }

        $match = in_array($country, $preferredLocations, true)
            || in_array($city, $preferredLocations, true);

        return [
            'points' => $match ? 10 : 0,
            'note' => $match
                ? 'Location matches one of the selected preferences'
                : 'Location does not match selected preferences',
        ];
    }

    protected function scoreStudyMode(Student $student, Program $program): array
    {
        $preferredModes = $this->normalizedPreferenceValues($student, 'preferred_study_mode');

        $mode = $this->normalizeText($program->study_mode);

        if (empty($preferredModes)) {
            return ['points' => 0, 'note' => 'No preferred study mode selected'];
        }

        if (!$mode) {
            return ['points' => 0, 'note' => 'Program study mode not available'];
        }

        if (in_array($mode, $preferredModes, true)) {
            return ['points' => 10, 'note' => 'Study mode matches one of the selected preferences'];
        }

        if (in_array('hybrid', $preferredModes, true) || $mode === 'hybrid') {
            return ['points' => 5, 'note' => 'Hybrid gives partial match'];
        }

        return ['points' => 0, 'note' => 'Study mode does not match selected preferences'];
    }

    protected function scoreCourseIntensity(Student $student, Program $program): array
    {
        $preferredIntensities = $this->normalizedPreferenceValues($student, 'preferred_course_intensity');

        $intensity = $this->normalizeText($program->course_intensity);

        if (empty($preferredIntensities)) {
            return ['points' => 0, 'note' => 'No preferred course intensity selected'];
        }

        if (!$intensity) {
            return ['points' => 0, 'note' => 'Program course intensity not available'];
        }

        $match = in_array($intensity, $preferredIntensities, true);

        return [
            'points' => $match ? 5 : 0,
            'note' => $match
                ? 'Course intensity matches one of the selected preferences'
                : 'Course intensity does not match selected preferences',
        ];
    }

    protected function scoreCategoryMatch(Student $student, Program $program): array
    {
        $studentCategories = $student->categories->pluck('name')->filter()->values()->all();

        $programCategory = $program->category?->name;

        if (empty($studentCategories) || !$programCategory) {
            return ['points' => 0, 'note' => 'Missing category data'];
        }

        $best = 0;

        foreach ($studentCategories as $studentCategory) {
            $best = max($best, $this->categorySimilarity($studentCategory, $programCategory));
        }

        return [
            'points' => round($best * 10, 1),
            'note' => $best >= 1
                ? 'Exact category match'
                : ($best > 0 ? 'Related category match' : 'No category relation'),
        ];
    }

    protected function scoreSubcategoryMatch(Student $student, Program $program): array
    {
        $studentSubcategories = $student->subcategories ?? collect();

        $programSubcategory = $program->subcategory;

        if ($studentSubcategories->isEmpty() || !$programSubcategory) {
            return ['points' => 0, 'note' => 'Missing sub category data'];
        }

        $programSubcategoryName = $this->normalizeText($programSubcategory->name);

        foreach ($studentSubcategories as $studentSubcategory) {
            if ($this->normalizeText($studentSubcategory->name) === $programSubcategoryName) {
                return ['points' => 10, 'note' => 'Exact sub category match'];
            }
        }

        $sameCategory = $studentSubcategories->contains(function ($studentSubcategory) use ($programSubcategory) {
            return (int) $studentSubcategory->category_id === (int) $programSubcategory->category_id;
        });

        if ($sameCategory) {
            return ['points' => 6, 'note' => 'Different sub category, but within the same category'];
        }

        return ['points' => 0, 'note' => 'No matching sub category'];
    }

    protected function scoreMajorRelevance(Student $student, Program $program): array
    {
        $studentLevel = $this->normalizeText($student->academic_level);

        $isHighSchool = str_contains($studentLevel, 'high');

        if ($isHighSchool) {
            return [
                'points' => 10,
                'note' => 'High school profile is not penalized for major relevance',
            ];
        }

        $major = $this->normalizeText($student->major);

        if (!$major) {
            return ['points' => 0, 'note' => 'Student major not available'];
        }

        $programCategory = $program->category?->name;
        $programSubcategory = $program->subcategory?->name;

        $majorCategory = $this->mapMajorToCategory($major);

        $categoryScore = $majorCategory && $programCategory
            ? $this->categorySimilarity($majorCategory, $programCategory)
            : 0;

        $subcategoryOverlap = $programSubcategory
            ? $this->tokenOverlap($major, $programSubcategory)
            : 0;

        $programNameOverlap = $this->tokenOverlap($major, $program->name ?? '');

        if ($subcategoryOverlap >= 2) {
            return ['points' => 10, 'note' => 'Major strongly aligns with the program sub category'];
        }

        if ($subcategoryOverlap === 1) {
            return ['points' => 8, 'note' => 'Major is closely related to the program sub category'];
        }

        if ($majorCategory && $programCategory && $majorCategory === $programCategory) {
            return ['points' => 6, 'note' => 'Major matches the same broad category'];
        }

        if ($categoryScore > 0) {
            return [
                'points' => round($categoryScore * 5, 1),
                'note' => 'Major is related at the category level',
            ];
        }

        if ($programNameOverlap > 0) {
            return ['points' => 4, 'note' => 'Major is textually related to the program name'];
        }

        return ['points' => 0, 'note' => 'Major is not related to the program'];
    }

    protected function effectiveTuitionForStudent(Student $student, Program $program): ?float
    {
        $studentNationality = $this->normalizeText($student->nationality ?: $student->country);

        $uniCountry = $this->normalizeText($program->university?->country);

        if ($uniCountry === 'lebanon') {
            if (in_array($studentNationality, ['lebanon', 'lebanese'], true)) {
                return $this->firstAvailableFee($program, ['leb_fees']);
            }

            return $this->firstAvailableFee($program, [
                'arab_fees',
                'eu_fees',
                'us_fees',
                'non_eu_fees',
                'pal_fees',
                'leb_fees',
            ]);
        }

        if (in_array($uniCountry, $this->euCountries, true)) {
            if ($this->isEuStudent($studentNationality)) {
                return $this->firstAvailableFee($program, ['eu_fees']);
            }

            return $this->firstAvailableFee($program, [
                'non_eu_fees',
                'us_fees',
                'arab_fees',
                'leb_fees',
                'pal_fees',
                'eu_fees',
            ]);
        }

        if (in_array($uniCountry, ['united states', 'usa', 'us'], true)) {
            if (in_array($studentNationality, ['united states', 'usa', 'us', 'american'], true)) {
                return $this->firstAvailableFee($program, ['us_fees']);
            }

            return $this->firstAvailableFee($program, [
                'non_eu_fees',
                'eu_fees',
                'arab_fees',
                'leb_fees',
                'pal_fees',
                'us_fees',
            ]);
        }

        return $this->firstAvailableFee($program, [
            'non_eu_fees',
            'eu_fees',
            'arab_fees',
            'leb_fees',
            'pal_fees',
            'us_fees',
        ]);
    }

    protected function firstAvailableFee(Program $program, array $columns): ?float
    {
        foreach ($columns as $column) {
            $value = $program->{$column};

            if (!is_null($value) && $value !== '') {
                return (float) $value;
            }
        }

        return null;
    }

    protected function isEuStudent(?string $nationality): bool
    {
        if (!$nationality) {
            return false;
        }

        return in_array($nationality, $this->euCountries, true);
    }

    protected function categorySimilarity(?string $a, ?string $b): float
    {
        if (!$a || !$b) {
            return 0;
        }

        if ($a === $b) {
            return 1;
        }

        if (isset($this->categoryRelations[$a][$b])) {
            return $this->categoryRelations[$a][$b];
        }

        if (isset($this->categoryRelations[$b][$a])) {
            return $this->categoryRelations[$b][$a];
        }

        return 0;
    }

    protected function mapMajorToCategory(string $major): ?string
    {
        $domains = $this->detectDomains($major);

        foreach ($domains as $domain) {
            if (isset($this->domainToCategory[$domain])) {
                return $this->domainToCategory[$domain];
            }
        }

        return null;
    }

    protected function detectDomains(string $text): array
    {
        $text = $this->normalizeText($text);

        $domains = [];

        foreach ($this->domainKeywords as $domain => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($text, $this->normalizeText($keyword))) {
                    $domains[] = $domain;
                    break;
                }
            }
        }

        return array_values(array_unique($domains));
    }

    protected function tokenOverlap(string $a, string $b): int
    {
        $a = $this->normalizeText($a);
        $b = $this->normalizeText($b);

        $tokensA = collect(explode(' ', $a))
            ->filter(fn ($token) => mb_strlen($token) > 2)
            ->values()
            ->all();

        $tokensB = collect(explode(' ', $b))
            ->filter(fn ($token) => mb_strlen($token) > 2)
            ->values()
            ->all();

        return count(array_intersect($tokensA, $tokensB));
    }

    protected function normalizeText(?string $value): string
    {
        $value = Str::lower(trim((string) $value));

        $value = str_replace(['-', '_'], ' ', $value);

        $value = preg_replace('/[^\pL\pN\s]/u', ' ', $value);

        $value = preg_replace('/\s+/', ' ', $value);

        return trim($value);
    }

    protected function normalizedPreferenceValues(Student $student, string $field): array
    {
        $rawValue = $student->getAttribute($field);

        return collect($this->preferenceValueArray($rawValue))
            ->map(fn ($value) => $this->normalizeText((string) $value))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    protected function preferenceValueArray($value): array
    {
        if (is_null($value) || $value === '') {
            return [];
        }

        if (is_array($value)) {
            return collect($value)
                ->flatten()
                ->filter()
                ->values()
                ->all();
        }

        if ($value instanceof Collection) {
            return $value
                ->flatten()
                ->filter()
                ->values()
                ->all();
        }

        if (is_string($value)) {
            $trimmed = trim($value);

            $decoded = json_decode($trimmed, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return collect($decoded)
                    ->flatten()
                    ->filter()
                    ->values()
                    ->all();
            }

            if (str_contains($trimmed, ',')) {
                return collect(explode(',', $trimmed))
                    ->map(fn ($item) => trim($item))
                    ->filter()
                    ->values()
                    ->all();
            }

            return [$trimmed];
        }

        return [];
    }

    protected function normalizeStudentGpa($studentGpa): ?float
    {
        if (is_null($studentGpa) || $studentGpa === '') {
            return null;
        }

        $studentGpa = (float) $studentGpa;

        if ($studentGpa > 10) {
            return round(($studentGpa / 100) * 4, 2);
        }

        return $studentGpa;
    }

    protected function formatGroupLabel(string $group): string
    {
        return match ($group) {
            'academic' => 'Academic',
            'preferences' => 'Preferences',
            'relevance' => 'Relevance',
            'cost' => 'Cost',
            default => Str::title($group),
        };
    }
}