<?php

namespace App\Imports;

use App\Models\University;
use App\Models\Program;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Language;
use App\Models\Progrem_Requirement;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use RuntimeException;

class UniversitiesProgramsImport implements OnEachRow, WithHeadingRow, WithChunkReading
{
    public function onRow(Row $excelRow): void
    {
        $row = $excelRow->toArray();

        $universityName = $this->extractUniversityName($row);
        $programName = $this->extractProgramName($row);

        if (!$universityName || !$programName) {
            return;
        }

        // 1) University
        $university = University::updateOrCreate(
            [
                'name'    => $universityName,
                'country' => $this->clean($row['country'] ?? $row['Country'] ?? null),
                'city'    => $this->clean($row['city'] ?? $row['City'] ?? null),
            ],
            [
                'rank'         => $this->toInt($row['rank'] ?? $row['Rank'] ?? null),
                'logo'         => $this->clean($row['logo'] ?? $row['Logo'] ?? null),
                'website_url'  => $this->clean($row['official_website'] ?? $row['Official Website'] ?? null),
                'image'        => $this->clean($row['image'] ?? $row['Image'] ?? null),
                'backup_image' => $this->clean($row['backup_image'] ?? $row['Backup Image'] ?? null),
                'description'  => $this->clean($row['description'] ?? $row['Description'] ?? null),
                'type'         => $this->clean($row['type'] ?? $row['Type'] ?? null),
                'insta'        => $this->clean($row['instagram'] ?? $row['Instagram'] ?? null),
                'linkedin'     => $this->clean($row['linkedin'] ?? $row['Linkedin'] ?? null),
                'facebook'     => $this->clean($row['facebook'] ?? $row['Facebook'] ?? null),
            ]
        );

        // 2) Category
        $rawCategory = $this->extractCategoryValue($row);
        $mappedCategoryName = $this->normalizeCategory($rawCategory);

        if (!$mappedCategoryName) {
            throw new RuntimeException("Category is empty for program: {$programName}");
        }

        $category = Category::whereRaw(
            'LOWER(TRIM(name)) = ?',
            [mb_strtolower(trim($mappedCategoryName))]
        )->first();

        if (!$category) {
            throw new RuntimeException("Category not found in categories table: {$mappedCategoryName}");
        }

        // 3) Sub Category
        $rawSubCategory = $this->extractSubCategoryValue($row);
        $mappedSubCategoryName = $this->normalizeSubCategory($rawSubCategory);

        $subCategory = null;

        if ($mappedSubCategoryName) {
            $subCategory = SubCategory::where('category_id', $category->id)
                ->whereRaw(
                    'LOWER(TRIM(name)) = ?',
                    [mb_strtolower(trim($mappedSubCategoryName))]
                )
                ->first();

            if (!$subCategory) {
                throw new RuntimeException(
                    "Sub category not found for category [{$category->name}]: {$mappedSubCategoryName}"
                );
            }
        }

        // 4) Requirements
        $requirement = Progrem_Requirement::firstOrCreate(
            [
                'sat'         => $this->toInt($row['sat'] ?? $row['SAT'] ?? null),
                'ielts'       => $this->toFloat($row['ielts'] ?? $row['IELTS'] ?? null),
                'toefl'       => $this->toInt($row['toefl'] ?? $row['TOEFL'] ?? null),
                'minimum_gpa' => $this->toFloat($row['gpa'] ?? $row['GPA'] ?? null),
            ]
        );

        // 5) Program
        $program = Program::create([
    'university_id'            => $university->id,
    'program_requirement_id'   => $requirement->id,
    'category_id'              => $category->id,
    'subcategory_id'           => $subCategory?->id,
    'name'                     => $programName,
    'description'              => null,
    'image'                    => null,
    'course_intensity'         => $this->normalizeCourseIntensity($row['course_intensity'] ?? null),
    'level'                    => $this->normalizeLevel($row['study_level'] ?? null),
    'url'                      => $this->clean($row['program_url'] ?? null),
    'study_mode'               => $this->normalizeStudyMode($row['study_mode'] ?? null),
    'duration'                 => $this->clean($row['programme_duration'] ?? null),
    'eu_fees'                  => $this->toMoney($row['eu'] ?? null),
    'non_eu_fees'              => $this->toMoney($row['non_eu'] ?? null),
    'arab_fees'                => $this->toMoney($row['arab'] ?? null),
    'leb_fees'                 => $this->toMoney($row['lebanese'] ?? null),
    'pal_fees'                 => $this->toMoney($row['pal'] ?? null),
    'us_fees'                  => $this->toMoney($row['us'] ?? null),
]);

        // 6) Languages
        $languageColumnMap = [
            'english' => 'English',
            'french'  => 'French',
            'arabic'  => 'Arabic',
            'german'  => 'German',
            'spanish' => 'Spanish',
            'catalan' => 'Catalan',
            'italian' => 'Italian',
        ];

        $languageIds = [];

        foreach ($languageColumnMap as $column => $languageName) {
            $value = $row[$column] ?? $row[ucfirst($column)] ?? 0;

            if ($this->isBinaryOne($value)) {
                $language = Language::firstOrCreate([
                    'name' => $languageName,
                ]);

                $languageIds[] = $language->id;
            }
        }

        $program->languages()->sync($languageIds);
    }

    public function chunkSize(): int
    {
        return 500;
    }

    private function extractUniversityName(array $row): ?string
    {
        return $this->clean(
            $row['university_name']
            ?? $row['University Name']
            ?? $row['university name']
            ?? null
        );
    }

    private function extractProgramName(array $row): ?string
    {
        return $this->clean(
            $row['program_name']
            ?? $row['Program Name']
            ?? $row['program name']
            ?? null
        );
    }

    private function extractCategoryValue(array $row): ?string
    {
        return $this->clean(
            $row['program_category']
            ?? $row['Program Category']
            ?? $row['program category']
            ?? null
        );
    }

    private function extractSubCategoryValue(array $row): ?string
    {
        return $this->clean(
            $row['program_sub_category']
            ?? $row['program_subcategory']
            ?? $row['sub_category']
            ?? $row['subcategory']
            ?? $row['subcategories']
            ?? $row['Program SubCategory']
            ?? $row['Program Sub Category']
            ?? $row['program subcategory']
            ?? $row['program sub category']
            ?? $row['sub_category_name']
            ?? null
        );
    }

    private function clean($value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    private function toInt($value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        $cleaned = preg_replace('/[^\d]/', '', (string) $value);

        return $cleaned === '' ? null : (int) $cleaned;
    }

    private function toFloat($value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        $cleaned = preg_replace('/[^\d.]/', '', (string) $value);

        return $cleaned === '' ? null : (float) $cleaned;
    }

    private function toMoney($value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        $cleaned = preg_replace('/[^\d.]/', '', (string) $value);

        return $cleaned === '' ? null : (float) $cleaned;
    }

    private function isBinaryOne($value): bool
    {
        return in_array((string) $value, ['1', '1.0'], true) || $value === 1 || $value === 1.0;
    }

    private function normalizeLevel(?string $value): ?string
    {
        $value = $this->clean($value);

        if (!$value) {
            return null;
        }

        $value = mb_strtolower($value);

        return match ($value) {
            'master', 'masters' => 'Master',
            'bachelor', 'bechalor' => 'Bachelor',
            default => Str::title($value),
        };
    }

    private function normalizeCourseIntensity(?string $value): ?string
    {
        $value = $this->clean($value);

        if (!$value) {
            return null;
        }

        $value = mb_strtolower($value);

        return match ($value) {
            'full time', 'full-time' => 'Full Time',
            'part time', 'part-time' => 'Part Time',
            default => Str::title($value),
        };
    }

    private function normalizeStudyMode(?string $value): ?string
    {
        $value = $this->clean($value);

        if (!$value) {
            return null;
        }

        $value = mb_strtolower($value);

        return match ($value) {
            'on campus', 'on-campus', 'campus' => 'On Campus',
            'online' => 'Online',
            'hybrid', 'blended' => 'Hybrid',
            default => Str::title($value),
        };
    }

    private function normalizeCategory(?string $value): ?string
    {
        $value = $this->clean($value);

        if (!$value) {
            return '';
        }

        $normalized = mb_strtolower(trim($value));

        $map = [
            'engineering' => 'Engineering',
            'engineering & technology' => 'Engineering',
            'mechanical engineering' => 'Engineering',
            'industrial engineering' => 'Engineering',
            'civil engineering' => 'Engineering',
            'structural engineering' => 'Engineering',
            'construction engineering' => 'Engineering',
            'electrical engineering' => 'Engineering',
            'electronic engineering' => 'Engineering',
            'communications engineering' => 'Engineering',
            'chemical engineering' => 'Engineering',
            'petroleum engineering' => 'Engineering',
            'materials engineering' => 'Engineering',
            'mechatronics' => 'Engineering',

            'computing' => 'Computing & Technology',
            'computing & technology' => 'Computing & Technology',
            'computer science' => 'Computing & Technology',
            'software engineering' => 'Computing & Technology',
            'information systems' => 'Computing & Technology',
            'information technology' => 'Computing & Technology',
            'computer engineering' => 'Computing & Technology',
            'embedded systems' => 'Computing & Technology',
            'robotics' => 'Computing & Technology',
            'cybersecurity' => 'Computing & Technology',
            'networks' => 'Computing & Technology',
            'cloud computing' => 'Computing & Technology',

            'data science' => 'Data Science & Artificial Intelligence',
            'data science & ai' => 'Data Science & Artificial Intelligence',
            'data science and ai' => 'Data Science & Artificial Intelligence',
            'data science & artificial intelligence' => 'Data Science & Artificial Intelligence',
            'artificial intelligence' => 'Data Science & Artificial Intelligence',
            'machine learning' => 'Data Science & Artificial Intelligence',
            'business analytics' => 'Data Science & Artificial Intelligence',
            'decision science' => 'Data Science & Artificial Intelligence',
            'intelligent systems' => 'Data Science & Artificial Intelligence',

            'health & medicine' => 'Health & Medicine',
            'medicine' => 'Health & Medicine',
            'dentistry' => 'Health & Medicine',
            'clinical medicine' => 'Health & Medicine',
            'nursing' => 'Health & Medicine',
            'midwifery' => 'Health & Medicine',
            'patient care' => 'Health & Medicine',
            'allied health' => 'Health & Medicine',
            'physiotherapy' => 'Health & Medicine',
            'rehabilitation' => 'Health & Medicine',
            'pharmacy' => 'Health & Medicine',
            'pharmacology' => 'Health & Medicine',
            'pharmaceutical sciences' => 'Health & Medicine',
            'public health' => 'Health & Medicine',
            'nutrition' => 'Health & Medicine',
            'health management' => 'Health & Medicine',

            'life sciences' => 'Life Sciences',
            'biology' => 'Life Sciences',
            'biological sciences' => 'Life Sciences',
            'genetics' => 'Life Sciences',
            'molecular biology' => 'Life Sciences',
            'biotechnology' => 'Life Sciences',
            'biomedical sciences' => 'Life Sciences',
            'bioinformatics' => 'Life Sciences',
            'neuroscience' => 'Life Sciences',
            'microbiology' => 'Life Sciences',

            'natural sciences' => 'Natural & Physical Sciences',
            'natural & physical sciences' => 'Natural & Physical Sciences',
            'mathematics' => 'Natural & Physical Sciences',
            'statistics' => 'Natural & Physical Sciences',
            'actuarial science' => 'Natural & Physical Sciences',
            'physics' => 'Natural & Physical Sciences',
            'astronomy' => 'Natural & Physical Sciences',
            'space science' => 'Natural & Physical Sciences',
            'chemistry' => 'Natural & Physical Sciences',
            'earth sciences' => 'Natural & Physical Sciences',
            'geology' => 'Natural & Physical Sciences',
            'geophysics' => 'Natural & Physical Sciences',

            'social sciences' => 'Social Sciences',
            'economics' => 'Social Sciences',
            'econometrics' => 'Social Sciences',
            'development studies' => 'Social Sciences',
            'political science' => 'Social Sciences',
            'international relations' => 'Social Sciences',
            'public affairs' => 'Social Sciences',
            'sociology' => 'Social Sciences',
            'anthropology' => 'Social Sciences',
            'human geography' => 'Social Sciences',

            'business and management' => 'Business & Management',
            'business & management' => 'Business & Management',
            'business administration' => 'Business & Management',
            'management' => 'Business & Management',
            'entrepreneurship' => 'Business & Management',
            'finance' => 'Business & Management',
            'accounting' => 'Business & Management',
            'banking' => 'Business & Management',
            'fintech' => 'Business & Management',
            'marketing' => 'Business & Management',
            'supply chain' => 'Business & Management',
            'logistics' => 'Business & Management',
            'operations' => 'Business & Management',

            'law & governance' => 'Law & Governance',
            'law and governance' => 'Law & Governance',
            'law' => 'Law & Governance',
            'legal studies' => 'Law & Governance',
            'justice' => 'Law & Governance',
            'governance' => 'Law & Governance',
            'public policy' => 'Law & Governance',
            'international law' => 'Law & Governance',

            'education' => 'Education',
            'teaching' => 'Education',
            'curriculum studies' => 'Education',
            'educational leadership' => 'Education',
            'special education' => 'Education',
            'instruction' => 'Education',

            'arts, design and architecture' => 'Arts, Design & Architecture',
            'arts, design & architecture' => 'Arts, Design & Architecture',
            'architecture' => 'Arts, Design & Architecture',
            'urban planning' => 'Arts, Design & Architecture',
            'built environment' => 'Arts, Design & Architecture',
            'graphic design' => 'Arts, Design & Architecture',
            'interior design' => 'Arts, Design & Architecture',
            'industrial design' => 'Arts, Design & Architecture',
            'fashion design' => 'Arts, Design & Architecture',
            'fine arts' => 'Arts, Design & Architecture',
            'visual arts' => 'Arts, Design & Architecture',
            'creative practice' => 'Arts, Design & Architecture',
            'music' => 'Arts, Design & Architecture',
            'performing arts' => 'Arts, Design & Architecture',
            'creative technology' => 'Arts, Design & Architecture',

            'humanities' => 'Humanities & Languages',
            'languages' => 'Humanities & Languages',
            'humanities & languages' => 'Humanities & Languages',
            'linguistics' => 'Humanities & Languages',
            'translation' => 'Humanities & Languages',
            'applied languages' => 'Humanities & Languages',
            'literature' => 'Humanities & Languages',
            'history' => 'Humanities & Languages',
            'philosophy' => 'Humanities & Languages',
            'religious studies' => 'Humanities & Languages',
            'cultural studies' => 'Humanities & Languages',
            'area studies' => 'Humanities & Languages',
            'heritage' => 'Humanities & Languages',

            'environment' => 'Environment & Agriculture',
            'environment & agriculture' => 'Environment & Agriculture',
            'environmental science' => 'Environment & Agriculture',
            'sustainability' => 'Environment & Agriculture',
            'ecology' => 'Environment & Agriculture',
            'agriculture' => 'Environment & Agriculture',
            'forestry' => 'Environment & Agriculture',
            'food science' => 'Environment & Agriculture',
            'natural resources' => 'Environment & Agriculture',

            'media & communication' => 'Media & Communication',
            'media' => 'Media & Communication',
            'communication' => 'Media & Communication',
            'journalism' => 'Media & Communication',
            'public relations' => 'Media & Communication',
            'film' => 'Media & Communication',
            'digital media' => 'Media & Communication',
            'advertising' => 'Media & Communication',
            'content production' => 'Media & Communication',

            'hospitality & tourism' => 'Hospitality & Tourism',
            'hospitality' => 'Hospitality & Tourism',
            'tourism' => 'Hospitality & Tourism',
            'travel' => 'Hospitality & Tourism',
            'hotel management' => 'Hospitality & Tourism',

            'sports & events' => 'Sports & Events',
            'sports science' => 'Sports & Events',
            'exercise science' => 'Sports & Events',
            'athletic performance' => 'Sports & Events',
            'event management' => 'Sports & Events',
            'recreation' => 'Sports & Events',
            'leisure studies' => 'Sports & Events',

            'transport, aviation & maritime' => 'Transport, Aviation & Maritime',
            'aviation' => 'Transport, Aviation & Maritime',
            'aeronautics' => 'Transport, Aviation & Maritime',
            'aerospace transport' => 'Transport, Aviation & Maritime',
            'maritime' => 'Transport, Aviation & Maritime',
            'shipping' => 'Transport, Aviation & Maritime',
            'transport systems' => 'Transport, Aviation & Maritime',

            'interdisciplinary & emerging fields' => 'Interdisciplinary & Emerging Fields',
            'interdisciplinary and emerging fields' => 'Interdisciplinary & Emerging Fields',
            'interdisciplinary' => 'Interdisciplinary & Emerging Fields',
            'innovation' => 'Interdisciplinary & Emerging Fields',
            'emerging studies' => 'Interdisciplinary & Emerging Fields',

            'psychology & behavioral sciences' => 'Psychology & Behavioral Sciences',
            'psychology and behavioral sciences' => 'Psychology & Behavioral Sciences',
            'psychology' => 'Psychology & Behavioral Sciences',
            'behavioral sciences' => 'Psychology & Behavioral Sciences',
            'behavioural sciences' => 'Psychology & Behavioral Sciences',
            'counseling' => 'Psychology & Behavioral Sciences',
            'mental health' => 'Psychology & Behavioral Sciences',
            'cognitive science' => 'Psychology & Behavioral Sciences',
            'behavioral science' => 'Psychology & Behavioral Sciences',
            'behavioural science' => 'Psychology & Behavioral Sciences',
        ];

        return $map[$normalized] ?? Str::title($value);
    }

    private function normalizeSubCategory(?string $value): ?string
    {
        $value = $this->clean($value);

        if (!$value) {
            return null;
        }

        $normalized = mb_strtolower(trim($value));

        $map = [
            'mechanical engineering' => 'Mechanical, Manufacturing & Industrial Engineering',
            'manufacturing engineering' => 'Mechanical, Manufacturing & Industrial Engineering',
            'industrial engineering' => 'Mechanical, Manufacturing & Industrial Engineering',

            'civil engineering' => 'Civil, Structural & Construction Engineering',
            'structural engineering' => 'Civil, Structural & Construction Engineering',
            'construction engineering' => 'Civil, Structural & Construction Engineering',

            'electrical engineering' => 'Electrical, Electronic & Communications Engineering',
            'electronic engineering' => 'Electrical, Electronic & Communications Engineering',
            'communications engineering' => 'Electrical, Electronic & Communications Engineering',
            'telecommunications engineering' => 'Electrical, Electronic & Communications Engineering',

            'chemical engineering' => 'Chemical, Process & Petroleum Engineering',
            'process engineering' => 'Chemical, Process & Petroleum Engineering',
            'petroleum engineering' => 'Chemical, Process & Petroleum Engineering',

            'materials engineering' => 'Materials, Mechatronics & General Engineering',
            'mechatronics' => 'Materials, Mechatronics & General Engineering',
            'general engineering' => 'Materials, Mechatronics & General Engineering',

            'computer science' => 'Computer Science, Software & Information Systems',
            'software engineering' => 'Computer Science, Software & Information Systems',
            'information systems' => 'Computer Science, Software & Information Systems',
            'information technology' => 'Computer Science, Software & Information Systems',

            'computer engineering' => 'Computer Engineering, Embedded Systems & Robotics',
            'embedded systems' => 'Computer Engineering, Embedded Systems & Robotics',
            'robotics' => 'Computer Engineering, Embedded Systems & Robotics',

            'cybersecurity' => 'Cybersecurity, Networks & Cloud Computing',
            'networks' => 'Cybersecurity, Networks & Cloud Computing',
            'networks and cloud computing' => 'Cybersecurity, Networks & Cloud Computing',
            'cloud computing' => 'Cybersecurity, Networks & Cloud Computing',

            'data science' => 'Data Science, Artificial Intelligence & Machine Learning',
            'artificial intelligence' => 'Data Science, Artificial Intelligence & Machine Learning',
            'machine learning' => 'Data Science, Artificial Intelligence & Machine Learning',

            'business analytics' => 'Business Analytics, Decision Science & Intelligent Systems',
            'decision science' => 'Business Analytics, Decision Science & Intelligent Systems',
            'intelligent systems' => 'Business Analytics, Decision Science & Intelligent Systems',

            'medicine' => 'Medicine, Dentistry & Clinical Medicine',
            'dentistry' => 'Medicine, Dentistry & Clinical Medicine',
            'clinical medicine' => 'Medicine, Dentistry & Clinical Medicine',

            'nursing' => 'Nursing, Midwifery & Patient Care',
            'midwifery' => 'Nursing, Midwifery & Patient Care',
            'patient care' => 'Nursing, Midwifery & Patient Care',

            'allied health' => 'Allied Health, Physiotherapy & Rehabilitation',
            'physiotherapy' => 'Allied Health, Physiotherapy & Rehabilitation',
            'rehabilitation' => 'Allied Health, Physiotherapy & Rehabilitation',

            'pharmacy' => 'Pharmacy, Pharmacology & Pharmaceutical Sciences',
            'pharmacology' => 'Pharmacy, Pharmacology & Pharmaceutical Sciences',
            'pharmaceutical sciences' => 'Pharmacy, Pharmacology & Pharmaceutical Sciences',

            'public health' => 'Public Health, Nutrition & Health Management',
            'nutrition' => 'Public Health, Nutrition & Health Management',
            'health management' => 'Public Health, Nutrition & Health Management',

            'biological sciences' => 'Biological Sciences, Genetics & Molecular Biology',
            'genetics' => 'Biological Sciences, Genetics & Molecular Biology',
            'molecular biology' => 'Biological Sciences, Genetics & Molecular Biology',

            'biotechnology' => 'Biotechnology, Biomedical Sciences & Bioinformatics',
            'biomedical sciences' => 'Biotechnology, Biomedical Sciences & Bioinformatics',
            'bioinformatics' => 'Biotechnology, Biomedical Sciences & Bioinformatics',

            'neuroscience' => 'Neuroscience, Microbiology & Life Science Research',
            'microbiology' => 'Neuroscience, Microbiology & Life Science Research',
            'life science research' => 'Neuroscience, Microbiology & Life Science Research',

            'mathematics' => 'Mathematics, Statistics & Actuarial Science',
            'statistics' => 'Mathematics, Statistics & Actuarial Science',
            'actuarial science' => 'Mathematics, Statistics & Actuarial Science',

            'physics' => 'Physics, Astronomy & Space Science',
            'astronomy' => 'Physics, Astronomy & Space Science',
            'space science' => 'Physics, Astronomy & Space Science',

            'chemistry' => 'Chemistry & Chemical Sciences',
            'chemical sciences' => 'Chemistry & Chemical Sciences',

            'earth sciences' => 'Earth Sciences, Geology & Geophysics',
            'geology' => 'Earth Sciences, Geology & Geophysics',
            'geophysics' => 'Earth Sciences, Geology & Geophysics',

            'economics' => 'Economics, Econometrics & Development Studies',
            'econometrics' => 'Economics, Econometrics & Development Studies',
            'development studies' => 'Economics, Econometrics & Development Studies',

            'political science' => 'Political Science, International Relations & Public Affairs',
            'international relations' => 'Political Science, International Relations & Public Affairs',
            'public affairs' => 'Political Science, International Relations & Public Affairs',

            'sociology' => 'Sociology, Anthropology & Human Geography',
            'anthropology' => 'Sociology, Anthropology & Human Geography',
            'human geography' => 'Sociology, Anthropology & Human Geography',

            'business administration' => 'Business Administration, Management & Entrepreneurship',
            'management' => 'Business Administration, Management & Entrepreneurship',
            'entrepreneurship' => 'Business Administration, Management & Entrepreneurship',

            'finance' => 'Finance, Accounting, Banking & Fintech',
            'accounting' => 'Finance, Accounting, Banking & Fintech',
            'banking' => 'Finance, Accounting, Banking & Fintech',
            'fintech' => 'Finance, Accounting, Banking & Fintech',

            'marketing' => 'Marketing, Supply Chain, Logistics & Operations',
            'supply chain' => 'Marketing, Supply Chain, Logistics & Operations',
            'logistics' => 'Marketing, Supply Chain, Logistics & Operations',
            'operations' => 'Marketing, Supply Chain, Logistics & Operations',

            'law' => 'Law, Legal Studies & Justice',
            'legal studies' => 'Law, Legal Studies & Justice',
            'justice' => 'Law, Legal Studies & Justice',

            'governance' => 'Governance, Public Policy & International Law',
            'public policy' => 'Governance, Public Policy & International Law',
            'international law' => 'Governance, Public Policy & International Law',

            'education' => 'Education, Teaching & Curriculum Studies',
            'teaching' => 'Education, Teaching & Curriculum Studies',
            'curriculum studies' => 'Education, Teaching & Curriculum Studies',

            'educational leadership' => 'Educational Leadership, Special Education & Instruction',
            'special education' => 'Educational Leadership, Special Education & Instruction',
            'instruction' => 'Educational Leadership, Special Education & Instruction',

            'architecture' => 'Architecture, Urban Planning & Built Environment',
            'urban planning' => 'Architecture, Urban Planning & Built Environment',
            'built environment' => 'Architecture, Urban Planning & Built Environment',

            'graphic design' => 'Graphic, Interior, Industrial & Fashion Design',
            'interior design' => 'Graphic, Interior, Industrial & Fashion Design',
            'industrial design' => 'Graphic, Interior, Industrial & Fashion Design',
            'fashion design' => 'Graphic, Interior, Industrial & Fashion Design',

            'fine arts' => 'Fine Arts, Visual Arts & Creative Practice',
            'visual arts' => 'Fine Arts, Visual Arts & Creative Practice',
            'creative practice' => 'Fine Arts, Visual Arts & Creative Practice',

            'music' => 'Music, Performing Arts & Creative Technology',
            'performing arts' => 'Music, Performing Arts & Creative Technology',
            'creative technology' => 'Music, Performing Arts & Creative Technology',

            'languages' => 'Languages, Linguistics, Translation & Applied Languages',
            'linguistics' => 'Languages, Linguistics, Translation & Applied Languages',
            'translation' => 'Languages, Linguistics, Translation & Applied Languages',
            'applied languages' => 'Languages, Linguistics, Translation & Applied Languages',

            'literature' => 'Literature, History, Philosophy & Religious Studies',
            'history' => 'Literature, History, Philosophy & Religious Studies',
            'philosophy' => 'Literature, History, Philosophy & Religious Studies',
            'religious studies' => 'Literature, History, Philosophy & Religious Studies',

            'cultural studies' => 'Cultural Studies, Area Studies & Heritage',
            'area studies' => 'Cultural Studies, Area Studies & Heritage',
            'heritage' => 'Cultural Studies, Area Studies & Heritage',

            'environmental science' => 'Environmental Science, Sustainability & Ecology',
            'sustainability' => 'Environmental Science, Sustainability & Ecology',
            'ecology' => 'Environmental Science, Sustainability & Ecology',

            'agriculture' => 'Agriculture, Forestry, Food Science & Natural Resources',
            'forestry' => 'Agriculture, Forestry, Food Science & Natural Resources',
            'food science' => 'Agriculture, Forestry, Food Science & Natural Resources',
            'natural resources' => 'Agriculture, Forestry, Food Science & Natural Resources',

            'media' => 'Media, Communication, Journalism & Public Relations',
            'communication' => 'Media, Communication, Journalism & Public Relations',
            'journalism' => 'Media, Communication, Journalism & Public Relations',
            'public relations' => 'Media, Communication, Journalism & Public Relations',

            'film' => 'Film, Digital Media, Advertising & Content Production',
            'digital media' => 'Film, Digital Media, Advertising & Content Production',
            'advertising' => 'Film, Digital Media, Advertising & Content Production',
            'content production' => 'Film, Digital Media, Advertising & Content Production',

            'hospitality' => 'Hospitality, Tourism, Travel & Hotel Management',
            'tourism' => 'Hospitality, Tourism, Travel & Hotel Management',
            'travel' => 'Hospitality, Tourism, Travel & Hotel Management',
            'hotel management' => 'Hospitality, Tourism, Travel & Hotel Management',

            'sports science' => 'Sports Science, Exercise Science & Athletic Performance',
            'exercise science' => 'Sports Science, Exercise Science & Athletic Performance',
            'athletic performance' => 'Sports Science, Exercise Science & Athletic Performance',

            'event management' => 'Event Management, Recreation & Leisure Studies',
            'recreation' => 'Event Management, Recreation & Leisure Studies',
            'leisure studies' => 'Event Management, Recreation & Leisure Studies',

            'aviation' => 'Aviation, Aeronautics & Aerospace Transport',
            'aeronautics' => 'Aviation, Aeronautics & Aerospace Transport',
            'aerospace transport' => 'Aviation, Aeronautics & Aerospace Transport',

            'maritime' => 'Maritime, Shipping, Logistics & Transport Systems',
            'shipping' => 'Maritime, Shipping, Logistics & Transport Systems',
            'transport systems' => 'Maritime, Shipping, Logistics & Transport Systems',

            'interdisciplinary' => 'Interdisciplinary, Innovation & Emerging Studies',
            'innovation' => 'Interdisciplinary, Innovation & Emerging Studies',
            'emerging studies' => 'Interdisciplinary, Innovation & Emerging Studies',

            'psychology' => 'Psychology, Counseling & Mental Health',
            'counseling' => 'Psychology, Counseling & Mental Health',
            'mental health' => 'Psychology, Counseling & Mental Health',

            'cognitive science' => 'Cognitive Science & Behavioral Science',
            'behavioral science' => 'Cognitive Science & Behavioral Science',
            'behavioural science' => 'Cognitive Science & Behavioral Science',
        ];

        return $map[$normalized] ?? trim($value);
    }
}