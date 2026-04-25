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
        Schema::table('success_stories', function (Blueprint $table) {
            $table->string('full_name')->nullable()->after('student_id');
            $table->string('email')->nullable()->after('full_name');
            $table->string('phone')->nullable()->after('email');
            $table->string('profile_image')->default('images/guest.png')->after('story_text');
            $table->string('status')->default('pending')->after('profile_image');

            $table->dropColumn('title');
            $table->dropColumn('is_published');
        });
    }

    public function down(): void
    {
        Schema::table('success_stories', function (Blueprint $table) {
            $table->string('title')->nullable();
            $table->boolean('is_published')->default(false);

            $table->dropColumn([
                'full_name',
                'email',
                'phone',
                'profile_image',
                'status',
            ]);
        });
    }
};
