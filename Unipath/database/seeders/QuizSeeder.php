<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quiz;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        Quiz::firstOrCreate(
            ['title' => 'Major Recommendation Quiz'],
            ['description' => 'A quiz that recommends suitable majors based on student interests and preferences.']
        );
    }
}