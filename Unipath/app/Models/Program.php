<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\University;
use App\Models\Category;
use App\Models\Language;
use App\Models\Recommendation;
use App\Models\Student;

class Program extends Model
{
    protected $table = 'programs';

    protected $fillable = [
        'university_id',
        'program_requirement_id',
        'category_id',
        'subcategory_id',
        'name',
        'course_intensity',
        'level',
        'url',
        'study_mode',
        'duration',
        'eu_fees',
        'non_eu_fees',
        'arab_fees',
        'leb_fees',
        'pal_fees',
        'us_fees'
    ];

    public function university()
    {
        return $this->belongsTo(University::class, 'university_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function favorites()
    {
        return $this->belongsToMany(Student::class, 'favorites', 'program_id', 'student_id');
    }

    public function languages()
    {
        return $this->belongsToMany(Language::class, 'language_program', 'program_id', 'language_id');
    }

    public function recommendations()
    {
        return $this->hasMany(Recommendation::class, 'program_id');
    }


    public function requirement()
    {
       return $this->belongsTo(Progrem_Requirement::class, 'program_requirement_id');
    }

    public function subcategories()
    {
       return $this->belongsTo(SubCategory::class, 'subcategory_id');
    }

}
