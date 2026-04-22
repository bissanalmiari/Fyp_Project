<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\QuizAnswer;
use App\Models\QuizOptionMajorScores;

class QuizOption extends Model
{
    protected $table = 'quiz_options';

    protected $fillable = [
        'quiz_question_id',
        'option_text',
        'order_index',
    ];

    public function quizAnswers()
    {
        return $this->hasMany(QuizAnswer::class, 'quiz_option_id');
    }

    public function quizQuestion()
    {
        return $this->belongsTo(QuizQuestion::class, 'quiz_question_id');
    }

    public function majorScores()
    {
        return $this->hasMany(QuizOptionMajorScores::class, 'quiz_option_id');
    }
}