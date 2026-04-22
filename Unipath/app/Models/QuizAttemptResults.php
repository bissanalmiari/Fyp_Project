<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Major;
use App\Models\QuizAttempt; 
class QuizAttemptResults extends Model
{
     protected $table = 'quiz_attempt_results';

    protected $fillable = [
       'quiz_attempt_id',
        'major_id',
        'rank_position',
        'score', 
        'compatibility_percent'
    ];

    public function major()
    {
        return $this->belongsTo(Major::class, 'major_id');
    }

    public function attempt()
    {
        return $this->belongsTo(QuizAttempt::class, 'quiz_attempt_id');
    }
}
