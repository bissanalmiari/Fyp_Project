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
        Schema::create('majors', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->string('slug')->unique()->nullable();
            $table->string('image')->nullable();
            $table->string('details_image')->nullable();
            $table->text('short_description')->nullable();
            $table->text('what_you_study')->nullable();
            $table->text('career_paths')->nullable();
            $table->boolean('is_trendy')->default(false); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('majors');
    }
};
