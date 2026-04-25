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
            'story_text' => 'I had no idea what to study after high school. UniPath guided me step by step and helped me discover my passion for Computer Science. Today, I am studying at my dream university!',
            'is_published' => true
        ]);

        SuccessStory::create([
            'student_id' => 1,
            'story_text' => 'I was struggling between multiple majors, but UniPath made everything clear. The recommendation system showed me exactly what fits my skills and interests. Best decision ever!',
            'is_published' => true
        ]);
    }
}