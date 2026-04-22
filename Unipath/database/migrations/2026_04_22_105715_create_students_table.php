<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
             $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('image')->nullable();
            $table->string('academic_level')->nullable();
            $table->string('major')->nullable();
            $table->float('gpa')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('nationality')->nullable();
            $table->date('dob')->nullable();
            $table->string('preferred_location')->nullable();
            $table->string('preferred_study_mode')->nullable();
            $table->string('preferred_course_intensity')->nullable();
            $table->string('budget')->nullable();
            $table->float('sat')->nullable();
            $table->float('ielts')->nullable();
            $table->float('toefl')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
