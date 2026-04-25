<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SuccessStory;

class SuccessStorySeeder extends Seeder
{
    public function run(): void
    {
       SuccessStory::create([
            'student_id' => 1,
            'full_name' => 'Test Student',
            'email' => 'test@gmail.com',
            'phone' => null,
            'story_text' => 'I had no idea what to study after high school. UniPath guided me step by step and helped me discover my passion for Computer Science. Today, I am studying at my dream university!',
            'profile_image' => 'images/guest.png',
            'status' => 'approved',
        ]);

    }
}