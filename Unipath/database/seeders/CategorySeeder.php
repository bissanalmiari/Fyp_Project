<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Engineering',
            'Computing & Technology', 
            'Data Science & Artificial Intelligence', 
            'Health & Medicine', 
            'Life Sciences', 
            'Natural & Physical Sciences', 
            'Social Sciences', 
            'Business & Management', 
            'Law & Governance',
            'Education', 
            'Arts, Design & Architecture', 
            'Humanities & Languages', 
            'Environment & Agriculture', 
            'Media & Communication', 
            'Hospitality & Tourism', 
            'Sports & Events', 
            'Transport, Aviation & Maritime', 
            'Interdisciplinary & Emerging Fields',
            'Psychology & Behavioral Sciences' 
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name' => $category,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}