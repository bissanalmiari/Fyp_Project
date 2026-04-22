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
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
             $table->foreignId('university_id')->constrained()->onDelete('cascade');
            $table->foreignId('program_requirement_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('subcategory_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('course_intensity')->nullable();
            $table->string('level');
            $table->string('url')->nullable();
            $table->string('study_mode')->nullable();
            $table->string('duration')->nullable();
            $table->decimal('eu_fees')->nullable();
            $table->decimal('non_eu_fees')->nullable();
            $table->decimal('arab_fees')->nullable();
            $table->decimal('leb_fees')->nullable();
            $table->decimal('pal_fees')->nullable();
            $table->decimal('us_fees')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
