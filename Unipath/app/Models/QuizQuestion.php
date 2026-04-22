<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Quiz;
use App\Models\QuizOption;
use App\Models\QuizAnswer;

class QuizQuestion extends Model
{
    protected $table = 'quiz_questions';

    protected $fillable = [
        'quiz_id',
        'question_text',
        'track_key',
        'order_index',
        'is_active'
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    public function answers()
    {
        return $this->hasMany(QuizAnswer::class, 'quiz_question_id');
    }

    public function options()
    {
        return $this->hasMany(QuizOption::class, 'quiz_question_id')->orderBy('order_index');
    }
}