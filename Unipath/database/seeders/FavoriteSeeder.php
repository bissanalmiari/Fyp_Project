<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Program;

class FavoriteSeeder extends Seeder
{
    public function run(): void
    {
        $students = Student::all();
        $programs = Program::all();

        foreach ($students as $student) {
            $randomPrograms = $programs->random(3);

            foreach ($randomPrograms as $program) {
                $student->favorites()->attach($program->id);
            }
        }
    }
}