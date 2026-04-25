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
            'Data Science',
            'Cybersecurity',
            'Software Engineering',

            'Computer Engineering',
            'Mechanical Engineering',
            'Electrical Engineering',
            'Industrial Engineering',

            'Business Administration',
            'Marketing',
            'Finance',
            'Economics',

            'Nursing',
            'Dentistry',
            'Pharmacy',
            'Medical Laboratory Sciences',

            'Graphic Design',
            'Multimedia Design',
            'Interior Design',
            'Architecture',

            'Mathematics',
            'Physics',
            'Chemistry',
            'Biology',

            'Psychology',
            'Social Sciences',
            'Sociology',
            'Education',
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
