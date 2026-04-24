<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Language;
use App\Models\Program;
use App\Models\Progrem_Requirement;
use App\Models\SubCategory;
use App\Models\University;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgramCsvSeeder extends Seeder
{
    public function run(): void
    {
        $path = base_path('mm recom/Programs_encoded.csv');

        if (! file_exists($path)) {
            $this->command?->warn("Programs CSV not found at {$path}");
            return;
        }

        $handle = fopen($path, 'r');
        $headers = fgetcsv($handle);

        if (! $headers) {
            fclose($handle);
            return;
        }

        $headers = array_map(fn ($header) => trim((string) $header), $headers);

        DB::transaction(function () use ($handle, $headers) {
            while (($row = fgetcsv($handle)) !== false) {
                $record = array_combine($headers, array_pad($row, count($headers), null));

                if (! $record || $this->value($record, 'Program Name') === '') {
                    continue;
                }

                $university = University::updateOrCreate(
                    ['name' => $this->value($record, 'University Name')],
                    [
                        'country' => $this->value($record, 'Country') ?: 'Unknown',
                        'city' => $this->value($record, 'City') ?: 'Unknown',
                        'rank' => $this->number($record, 'Rank'),
                        'website_url' => $this->value($record, 'Official Website') ?: null,
                    ]
                );

                $category = Category::firstOrCreate([
                    'name' => $this->value($record, 'broad_category') ?: 'Interdisciplinary & Emerging Fields',
                ]);

                $subcategory = SubCategory::firstOrCreate(
                    [
                        'name' => $this->value($record, 'detailed_category') ?: 'Interdisciplinary, Innovation & Emerging Studies',
                    ],
                    [
                        'category_id' => $category->id,
                    ]
                );

                $requirement = Progrem_Requirement::create([
                    'sat' => $this->number($record, 'SAT'),
                    'ielts' => $this->number($record, 'IELTS'),
                    'toefl' => $this->number($record, 'TOEFL'),
                    'minimum_gpa' => $this->number($record, 'GPA'),
                ]);

                $program = Program::updateOrCreate(
                    [
                        'university_id' => $university->id,
                        'name' => $this->value($record, 'Program Name'),
                    ],
                    [
                        'program_requirement_id' => $requirement->id,
                        'category_id' => $category->id,
                        'subcategory_id' => $subcategory->id,
                        'course_intensity' => $this->value($record, 'Course Intensity') ?: null,
                        'level' => $this->value($record, 'Study Level') ?: 'Unknown',
                        'url' => $this->value($record, 'Program URL') ?: null,
                        'study_mode' => $this->value($record, 'Study Mode') ?: null,
                        'duration' => $this->value($record, 'Programme Duration') ?: null,
                        'eu_fees' => $this->number($record, 'eu'),
                        'non_eu_fees' => $this->number($record, 'non eu'),
                        'arab_fees' => $this->number($record, 'arab'),
                        'leb_fees' => $this->number($record, 'lebanese'),
                        'pal_fees' => $this->number($record, 'pal'),
                        'us_fees' => $this->number($record, 'us'),
                    ]
                );

                $languageIds = $this->languageIds($record);
                if (! empty($languageIds)) {
                    $program->languages()->sync($languageIds);
                }
            }
        });

        fclose($handle);
    }

    private function languageIds(array $record): array
    {
        $names = [];
        $languageText = $this->value($record, 'Languages');

        if ($languageText !== '') {
            $names = array_merge($names, preg_split('/[,;|\/]+/', $languageText));
        }

        foreach (['english', 'french', 'arabic', 'german', 'spanish', 'catalan', 'italian'] as $column) {
            if ((int) $this->number($record, $column) === 1) {
                $names[] = ucfirst($column);
            }
        }

        return collect($names)
            ->map(fn ($name) => trim((string) $name))
            ->filter()
            ->unique(fn ($name) => strtolower($name))
            ->map(fn ($name) => Language::firstOrCreate(['name' => $name])->id)
            ->values()
            ->all();
    }

    private function value(array $record, string $key): string
    {
        return trim((string) ($record[$key] ?? ''));
    }

    private function number(array $record, string $key): ?float
    {
        $value = str_replace([',', '$'], '', $this->value($record, $key));

        if ($value === '') {
            return null;
        }

        return is_numeric($value) ? (float) $value : null;
    }
}
