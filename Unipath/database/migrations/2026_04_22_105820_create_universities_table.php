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
        Schema::create('universities', function (Blueprint $table) {
            $table->id();
             $table->string('name');
            $table->string('country');
            $table->string('city');
            $table->integer('rank')->nullable();
            $table->string('logo')->nullable();
            $table->string('website_url')->nullable();
            $table->string('image')->nullable();
            $table->string('backup_image')->nullable();
            $table->text('description')->nullable();
            $table->string('type')->nullable();
            $table->string('insta')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('facebook')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('universities');
    }
};
