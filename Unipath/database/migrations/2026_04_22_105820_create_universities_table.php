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
            $table->text('logo')->nullable();
            $table->text('website_url')->nullable();
            $table->text('image')->nullable();
            $table->text('backup_image')->nullable();
            $table->text('description')->nullable();
            $table->string('type')->nullable();
            $table->text('insta')->nullable();
            $table->text('linkedin')->nullable();
            $table->text('facebook')->nullable();
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
