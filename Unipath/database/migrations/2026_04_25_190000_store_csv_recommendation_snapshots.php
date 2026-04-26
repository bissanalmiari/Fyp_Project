<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('recommendations', function (Blueprint $table) {
            if (! Schema::hasColumn('recommendations', 'program_name')) {
                $table->string('program_name')->nullable()->after('program_id');
            }

            if (! Schema::hasColumn('recommendations', 'university_name')) {
                $table->string('university_name')->nullable()->after('program_name');
            }

            if (! Schema::hasColumn('recommendations', 'country')) {
                $table->string('country')->nullable()->after('university_name');
            }

            if (! Schema::hasColumn('recommendations', 'program_level')) {
                $table->string('program_level')->nullable()->after('country');
            }

            if (! Schema::hasColumn('recommendations', 'study_mode')) {
                $table->string('study_mode')->nullable()->after('program_level');
            }

            if (! Schema::hasColumn('recommendations', 'course_intensity')) {
                $table->string('course_intensity')->nullable()->after('study_mode');
            }

            if (! Schema::hasColumn('recommendations', 'program_url')) {
                $table->text('program_url')->nullable()->after('course_intensity');
            }
        });

        Schema::table('recommendations', function (Blueprint $table) {
            $table->foreignId('program_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('recommendations', function (Blueprint $table) {
            $table->dropColumn([
                'program_name',
                'university_name',
                'country',
                'program_level',
                'study_mode',
                'course_intensity',
                'program_url',
            ]);
        });
    }
};
