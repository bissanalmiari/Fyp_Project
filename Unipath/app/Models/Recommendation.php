<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Program; 
use App\Models\Students;
use App\Models\FeedbackRecommendation;
class Recommendation extends Model
{
    protected $table = 'recommendations';

    protected $fillable = [
       'student_id',
        'program_id',
        'score',
        'rank', 
        'explanation'
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
