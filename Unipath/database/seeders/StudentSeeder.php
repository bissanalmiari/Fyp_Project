<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $students = [
            ['name' => 'Ali Hassan', 'email' => 'ali@example.com', 'username' => 'ali_hassan'],
            ['name' => 'Sara Khalil', 'email' => 'sara@example.com', 'username' => 'sara_khalil'],
            ['name' => 'Omar Nasser', 'email' => 'omar@example.com', 'username' => 'omar_nasser'],
            ['name' => 'Lina Farah', 'email' => 'lina@example.com', 'username' => 'lina_farah'],
            ['name' => 'Hassan Saab', 'email' => 'hassan@example.com', 'username' => 'hassan_saab'],
            ['name' => 'Maya Haddad', 'email' => 'maya@example.com', 'username' => 'maya_haddad'],
            ['name' => 'Karim Darwish', 'email' => 'karim@example.com', 'username' => 'karim_darwish'],
            ['name' => 'Nour Hamdan', 'email' => 'nour@example.com', 'username' => 'nour_hamdan'],
            ['name' => 'Youssef Mansour', 'email' => 'youssef@example.com', 'username' => 'youssef_mansour'],
            ['name' => 'Rana Salameh', 'email' => 'rana@example.com', 'username' => 'rana_salameh'],
        ];

        foreach ($students as $student) {
            User::create([
                'name' => $student['name'],
                'email' => $student['email'],
                'username' => $student['username'],
                'password' => Hash::make('password123'), // same password for all
            ]);
        }
    }
}