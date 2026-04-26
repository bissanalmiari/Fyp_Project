<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use App\Models\SuccessStory;
use Illuminate\Support\Facades\Hash;

class SuccessStorySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create or get user (FIXED: added username)
        $user = User::firstOrCreate(
            ['email' => 'test@gmail.com'],
            [
                'name' => 'Test Student',
                'username' => 'test_student',
                'password' => Hash::make('12345678'),
            ]
        );

        // 2. Create or get student linked to user
        $student = Student::firstOrCreate(
            ['user_id' => $user->id],
            [
                'academic_level' => 'High School',
                'major' => null,
                'gpa' => null,
                'country' => null,
                'city' => null,
                'nationality' => null,
                'dob' => null,
                'preferred_location' => null,
                'preferred_study_mode' => null,
                'preferred_course_intensity' => null,
                'budget' => null,
                'sat' => null,
                'ielts' => null,
                'toefl' => null,
            ]
        );

        // 3. Create or update success story (avoids duplicates)
        SuccessStory::updateOrCreate(
            ['email' => 'test@gmail.com'],
            [
                'student_id' => $student->id,
                'full_name' => 'Test Student',
                'phone' => null,
                'story_text' => 'I had no idea what to study after high school. UniPath guided me step by step and helped me discover my passion for Computer Science. Today, I am studying at my dream university!',
                'profile_image' => 'images/guest.png',
                'status' => 'approved',
            ]
        );
    }
}