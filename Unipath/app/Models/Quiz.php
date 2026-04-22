<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;

class Quiz extends Model
{
    protected $table = 'quizzes';
    protected $fillable = [
        'title',
        'description'
    ];

    public function questions()
    {
        return $this->hasMany(QuizQuestion::class, 'quiz_id');
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class, 'quiz_id');
    }
}
