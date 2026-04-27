<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();   
        $majors = [
    'Computer Science',
    'Business Administration',
    'Engineering',
    'Medicine',
    'Architecture',
    'Law',
    'Pharmacy',
    'Graphic Design'
];


foreach ($users as $user) {
    Student::create([
        'user_id' => $user->id,
        'academic_level' => 'Undergraduate',
        'major' => $majors[array_rand($majors)],
        'gpa' => rand(85, 90),
        'country' => 'Lebanon',
        'city' => 'Beirut',
        'nationality' => 'Lebanese',
        'dob' => Carbon::createFromDate(
    rand(1998, 2006),
    rand(1, 12),
    rand(1, 28)
),
        'preferred_location' => 'Lebanon',
        'preferred_study_mode' => 'On Campus',
        'preferred_course_intensity' => 'Full-time',
        'budget' => rand(5000, 20000),
        'sat' => rand(900, 1400),
        'ielts' => rand(5, 8),
        'toefl' => rand(60, 100),
    ]);
}
    }
}