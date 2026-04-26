<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\LanguageSeeder;
use Database\Seeders\MajorSeeder;
use Database\Seeders\CareerSeeder;
use Database\Seeders\QuizSeeder;
use Database\Seeders\QuizMajorSeeder;
use Database\Seeders\QuizMajorImageSeeder;
use Database\Seeders\MajorDetailsSeeder;
use Database\Seeders\QuizQuestionSeeder;
use Database\Seeders\QuizOptionMajorScoreSeeder;
use Database\Seeders\SubCategorySeeder;


class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // User::factory(10)->create();

       

        $this->call([
            QuizSeeder::class,
            QuizMajorSeeder::class,
            QuizMajorImageSeeder::class,
            MajorDetailsSeeder::class,
            QuizQuestionSeeder::class,
            QuizOptionMajorScoreSeeder::class,
            CategorySeeder::class,
            LanguageSeeder::class,
            MajorSeeder::class,
            CareerSeeder::class,
            SubCategorySeeder::class,

        ]);

        $this->call(SuccessStorySeeder::class);
    }
}
