<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\QuizAttemptResult; 
use App\Models\QuizOptionMajorScore;
use App\Models\MajorCareer;
class Major extends Model
{
    protected $table = 'majors';
    
    protected $fillable = [
        'name',
        'is_trendy',
        'image',
        'details_image',
        'slug',
        'short_description',
        'what_you_study',
        'career_paths',
    ];

    public function quizAttemptResults()
    {
        return $this->hasMany(QuizAttemptResult::class, 'major_id');
    }

    public function quizOptionMajorScores()
    {
        return $this->hasMany(QuizOptionMajorScore::class, 'major_id');
    }

    public function majorCareers()
    {
        return $this->hasMany(MajorCareer::class, 'major_id');
    }
}
