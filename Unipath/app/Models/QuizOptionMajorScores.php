<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\QuizOption;
use App\Models\Major;

class QuizOptionMajorScores extends Model
{
    protected $table = 'quiz_option_major_scores';

    protected $fillable = [
        'quiz_option_id',
        'major_id',
        'score_value',
    ];

    public function option()
    {
        return $this->belongsTo(QuizOption::class, 'quiz_option_id');
    }

    public function major()
    {
        return $this->belongsTo(Major::class, 'major_id');
    }
}
