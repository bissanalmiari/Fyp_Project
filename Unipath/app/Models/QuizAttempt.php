<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Quiz;
use App\Models\Student;
use App\Models\QuizAttemptFeedback;
use App\Models\QuizAnswer;
use App\Models\QuizAttemptResults;

class QuizAttempt extends Model
{
    protected $table = 'quiz_attempts';
    
    protected $fillable = [
        'student_id', 
        'quiz_id',
        'selected_track',
        'started_at',
        'completed_at'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function answers()
    {
        return $this->hasMany(QuizAnswer::class, 'quiz_attempt_id');
    }

    public function feedback()
    {
        return $this->hasMany(QuizAttemptFeedback::class);
    }

    public function quizAttemptResults()
    {
        return $this->hasMany(QuizAttemptResults::class, 'quiz_attempt_id');
    }
}
