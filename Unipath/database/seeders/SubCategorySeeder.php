<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubCategorySeeder extends Seeder
{
    public function run(): void
    {
        $subCategories = [

            // Engineering (1)
            ['category_id' => 1, 'name' => 'Mechanical, Manufacturing & Industrial Engineering'],
            ['category_id' => 1, 'name' => 'Civil, Structural & Construction Engineering'],
            ['category_id' => 1, 'name' => 'Electrical, Electronic & Communications Engineering'],
            ['category_id' => 1, 'name' => 'Chemical, Process & Petroleum Engineering'],
            ['category_id' => 1, 'name' => 'Materials, Mechatronics & General Engineering'],

            // Computing & Technology (2)
            ['category_id' => 2, 'name' => 'Computer Science, Software & Information Systems'],
            ['category_id' => 2, 'name' => 'Computer Engineering, Embedded Systems & Robotics'],
            ['category_id' => 2, 'name' => 'Cybersecurity, Networks & Cloud Computing'],

            // Data Science & AI (3)
            ['category_id' => 3, 'name' => 'Data Science, Artificial Intelligence & Machine Learning'],
            ['category_id' => 3, 'name' => 'Business Analytics, Decision Science & Intelligent Systems'],

            // Health & Medicine (4)
            ['category_id' => 4, 'name' => 'Medicine, Dentistry & Clinical Medicine'],
            ['category_id' => 4, 'name' => 'Nursing, Midwifery & Patient Care'],
            ['category_id' => 4, 'name' => 'Allied Health, Physiotherapy & Rehabilitation'],
            ['category_id' => 4, 'name' => 'Pharmacy, Pharmacology & Pharmaceutical Sciences'],
            ['category_id' => 4, 'name' => 'Public Health, Nutrition & Health Management'],

            // Life Sciences (5)
            ['category_id' => 5, 'name' => 'Biological Sciences, Genetics & Molecular Biology'],
            ['category_id' => 5, 'name' => 'Biotechnology, Biomedical Sciences & Bioinformatics'],
            ['category_id' => 5, 'name' => 'Neuroscience, Microbiology & Life Science Research'],

            // Natural Sciences (6)
            ['category_id' => 6, 'name' => 'Mathematics, Statistics & Actuarial Science'],
            ['category_id' => 6, 'name' => 'Physics, Astronomy & Space Science'],
            ['category_id' => 6, 'name' => 'Chemistry & Chemical Sciences'],
            ['category_id' => 6, 'name' => 'Earth Sciences, Geology & Geophysics'],

            // Social Sciences (7)
            ['category_id' => 7, 'name' => 'Economics, Econometrics & Development Studies'],
            ['category_id' => 7, 'name' => 'Political Science, International Relations & Public Affairs'],
            ['category_id' => 7, 'name' => 'Sociology, Anthropology & Human Geography'],

            // Business (8)
            ['category_id' => 8, 'name' => 'Business Administration, Management & Entrepreneurship'],
            ['category_id' => 8, 'name' => 'Finance, Accounting, Banking & Fintech'],
            ['category_id' => 8, 'name' => 'Marketing, Supply Chain, Logistics & Operations'],

            // Law (9)
            ['category_id' => 9, 'name' => 'Law, Legal Studies & Justice'],
            ['category_id' => 9, 'name' => 'Governance, Public Policy & International Law'],

            // Education (10)
            ['category_id' => 10, 'name' => 'Education, Teaching & Curriculum Studies'],
            ['category_id' => 10, 'name' => 'Educational Leadership, Special Education & Instruction'],

            // Architecture & Arts (11)
            ['category_id' => 11, 'name' => 'Architecture, Urban Planning & Built Environment'],
            ['category_id' => 11, 'name' => 'Graphic, Interior, Industrial & Fashion Design'],
            ['category_id' => 11, 'name' => 'Fine Arts, Visual Arts & Creative Practice'],
            ['category_id' => 11, 'name' => 'Music, Performing Arts & Creative Technology'],

            // Languages (12)
            ['category_id' => 12, 'name' => 'Languages, Linguistics, Translation & Applied Languages'],
            ['category_id' => 12, 'name' => 'Literature, History, Philosophy & Religious Studies'],
            ['category_id' => 12, 'name' => 'Cultural Studies, Area Studies & Heritage'],

            // Environment (13)
            ['category_id' => 13, 'name' => 'Environmental Science, Sustainability & Ecology'],
            ['category_id' => 13, 'name' => 'Agriculture, Forestry, Food Science & Natural Resources'],

            // Media (14)
            ['category_id' => 14, 'name' => 'Media, Communication, Journalism & Public Relations'],
            ['category_id' => 14, 'name' => 'Film, Digital Media, Advertising & Content Production'],

            // Hospitality (15)
            ['category_id' => 15, 'name' => 'Hospitality, Tourism, Travel & Hotel Management'],

            // Sports (16)
            ['category_id' => 16, 'name' => 'Sports Science, Exercise Science & Athletic Performance'],
            ['category_id' => 16, 'name' => 'Event Management, Recreation & Leisure Studies'],

            // Aviation (17)
            ['category_id' => 17, 'name' => 'Aviation, Aeronautics & Aerospace Transport'],
            ['category_id' => 17, 'name' => 'Maritime, Shipping, Logistics & Transport Systems'],

            // Interdisciplinary (18)
            ['category_id' => 18, 'name' => 'Interdisciplinary, Innovation & Emerging Studies'],

            // Psychology (19)
            ['category_id' => 19, 'name' => 'Psychology, Counseling & Mental Health '],
            ['category_id' => 19, 'name' => 'Cognitive Science & Behavioral Science '],
        ];

        foreach ($subCategories as $sub) {
            DB::table('subcategories')->insert([
                'category_id' => $sub['category_id'],
                'name' => $sub['name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}