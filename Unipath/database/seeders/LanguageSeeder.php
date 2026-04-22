<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        $languages = [
            ['name' => 'English'],
            ['name' => 'French'],
            ['name' => 'Arabic'],
            ['name' => 'German'],
            ['name' => 'Spanish'],
            ['name' => 'Catalan'],
            ['name' => 'Italian'],
        ];

        DB::table('languages')->insert($languages);
    }
}
