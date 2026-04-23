<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Major;

class QuizMajorImageSeeder extends Seeder
{
    public function run(): void
    {
      $images = [
        'Computer Science' => 'cs.png',
        'Computer Engineering' => 'cce.png',
        'Data Science' => 'data-science.png',
        'Cybersecurity' => 'cybersecurity.png',

        'Business Administration' => 'business-administration.png',
        'Marketing' => 'marketing.png',
        'Finance' => 'finance.png',
        'Economics' => 'economics.png',

        'Nursing' => 'nursing.png',
        'Psychology' => 'psychology.png',
        'Education' => 'education.png',
        'Biology' => 'biology.png',

        'Graphic Design' => 'graphic-design.png',
        'Mathematics' => 'maths.png',
        'Architecture' => 'architecture.png',
        'Multimedia Design' => 'multimedia-design.png',

        'Software Engineering' => 'software.png',
        'Mechanical Engineering' => 'mechanical.png',
        'Electrical Engineering' => 'electrical.png',
        'Industrial Engineering' => 'industrial.png',
    ];

        foreach ($images as $majorName => $imageFile) {
            Major::where('name', $majorName)->update([
                'image' => $imageFile,
            ]);
        }
    }
}