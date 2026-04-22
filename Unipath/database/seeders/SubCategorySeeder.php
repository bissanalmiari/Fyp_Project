<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubCategorySeeder extends Seeder
{
    public function run(): void
    {
        $subCategories = [
            ['category_id' => 1, 'name' => 'Mechanical, Manufacturing & Industrial Engineering'], 
            ['category_id' => 2, 'name' => 'Computer Science, Software & Information Systems'], 
            ['category_id' => 3, 'name' => 'Data Science, Artificial Intelligence & Machine Learning'], 
            ['category_id' => 4, 'name' => 'Medicine, Dentistry & Clinical Medicine'], 
            ['category_id' => 5, 'name' => 'Biological Sciences, Genetics & Molecular Biology'], 
            ['category_id' => 6, 'name' => 'Mathematics, Statistics & Actuarial Science'], 
            ['category_id' => 7, 'name' => 'Economics, Econometrics & Development Studies'], 
            ['category_id' => 8, 'name' => 'Business Administration, Management & Entrepreneurship'], 
            ['category_id' => 9, 'name' => 'Law, Legal Studies & Justice'], 
            ['category_id' => 10, 'name' => 'Education, Teaching & Curriculum Studies'], 
            ['category_id' => 11, 'name' => 'Architecture, Urban Planning & Built Environment'], 
            ['category_id' => 12, 'name' => 'Languages, Linguistics, Translation & Applied Languages'], 
            ['category_id' => 13, 'name' => 'Environmental Science, Sustainability & Ecology'], 
            ['category_id' => 14, 'name' => 'Media, Communication, Journalism & Public Relations'], 
            ['category_id' => 15, 'name' => 'Hospitality, Tourism, Travel & Hotel Management'], 
            ['category_id' => 16, 'name' => 'Sports Science, Exercise Science & Athletic Performance'], 
            ['category_id' => 17, 'name' => 'Aviation, Aeronautics & Aerospace Transport'], 
            ['category_id' => 18, 'name' => 'Interdisciplinary, Innovation & Emerging Studies'] 
        ];

        foreach ($subCategories as $sub) {
            DB::table('subcategories')->insert(array_merge($sub, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}