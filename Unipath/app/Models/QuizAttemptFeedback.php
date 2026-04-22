<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\QuizAttempt;
use App\Models\Student;

class QuizAttemptFeedback extends Model
{
     protected $table = 'quiz_attempt_feedback';

    protected $fillable = [
       'quiz_attempt_id',
        'student_id',
        'rating_value',
        'created_at'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function attempt()
    {
        return $this->belongsTo(QuizAttempt::class);
    }
}
