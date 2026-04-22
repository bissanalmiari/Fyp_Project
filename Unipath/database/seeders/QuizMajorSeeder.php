<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Major;

class QuizMajorSeeder extends Seeder
{
    public function run(): void
    {
        $majors = [
        'Computer Science',
        'Computer Engineering',
        'Data Science',
        'Cybersecurity',
        'Business Administration',
        'Marketing',
        'Finance',
        'Economics',
        'Nursing',
        'Psychology',
        'Education',
        'Biology',
        'Graphic Design',
        'Mathematics',
        'Architecture',
        'Multimedia Design',
    ];

    $trendyMajors = [
        'Computer Science',
        'Data Science',
        'Cybersecurity',
    ];

        foreach ($majors as $major) {
            Major::firstOrCreate(
                ['name' => $major],
                ['is_trendy' => in_array($major, $trendyMajors)]
            );
        }
    }
}
