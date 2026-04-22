<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\QuizOption;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;

class QuizAnswer extends Model
{
    protected $table = 'quiz_answers';

    protected $fillable = [
        'quiz_question_id',
        'quiz_option_id',
        'quiz_attempt_id',
    ];

    public function quizOption()
    {
        return $this->belongsTo(QuizOption::class, 'quiz_option_id');
    }

    public function quizAttempt()
    {
        return $this->belongsTo(QuizAttempt::class, 'quiz_attempt_id');
    }

    public function quizQuestion()
    {
        return $this->belongsTo(QuizQuestion::class, 'quiz_question_id');
    }
}