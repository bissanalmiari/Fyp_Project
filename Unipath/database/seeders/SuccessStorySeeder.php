<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SuccessStory;
use App\Models\Student;

class SuccessStorySeeder extends Seeder
{
    public function run(): void
    {
        $stories = [
            "UniPath helped me discover a major that truly fits my skills and interests.",
            "Thanks to UniPath, I got accepted into a university abroad with a scholarship.",
            "I was confused about my future, but UniPath guided me to the right career path.",
            "The recommendations were very accurate and helped me choose my university.",
            "UniPath made the whole decision process much easier and clearer for me.",
            "I found programs I didn’t even know existed, and now I’m pursuing my dream.",
            "The platform helped me match my GPA with the perfect university options.",
            "I improved my choices thanks to the personalized recommendations.",
            "UniPath gave me confidence in choosing my academic journey.",
            "I successfully applied to a program that fits my goals perfectly."
        ];

        $students = Student::all();

        foreach ($students as $student) {
            SuccessStory::create([
                'student_id' => $student->id,
                'full_name' => $student->user->name,
                'email' => $student->user->email,
                'phone' => '70' . rand(100000, 999999),
                'story_text' => $stories[array_rand($stories)],
                'profile_image' => 'default.png',
                'status' => 'pending',
            ]);
        }
    }
}