<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ContactMessage;

class ContactMessageSeeder extends Seeder
{
    public function run(): void
    {
        $messages = [
            "Hello, I need help choosing a major.",
            "What universities match my GPA?",
            "Can you recommend programs abroad?",
            "I’m interested in AI and Data Science.",
            "What are my chances for scholarships?"
        ];

        $students = [
            ['name' => 'Ali Hassan', 'email' => 'ali@example.com', 'phone' => '70123456'],
            ['name' => 'Sara Khalil', 'email' => 'sara@example.com', 'phone' => '70234567'],
            ['name' => 'Omar Nasser', 'email' => 'omar@example.com', 'phone' => '70345678'],
            ['name' => 'Lina Farah', 'email' => 'lina@example.com', 'phone' => '70456789'],
        ];

        foreach ($students as $student) {
            foreach ($messages as $msg) {
                ContactMessage::create([
                    'full_name' => $student['name'],
                    'email' => $student['email'],
                    'phone' => $student['phone'],
                    'message' => $msg,
                  
                ]);
            }
        }
    }
}