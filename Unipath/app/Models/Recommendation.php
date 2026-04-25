<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Program; 
use App\Models\FeedbackRecommendation;
class Recommendation extends Model
{
    protected $table = 'recommendations';

    protected $fillable = [
       'student_id',
        'program_id',
        'program_name',
        'university_name',
        'country',
        'program_level',
        'study_mode',
        'course_intensity',
        'program_url',
        'score',
        'rank', 
        'explanation',
        'preference_hash',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function feedbacks()
{
    return $this->hasMany(FeedbackRecommendation::class, 'recommendation_id');
}
}
