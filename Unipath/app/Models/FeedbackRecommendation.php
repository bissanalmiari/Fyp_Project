<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Recommendation;
class FeedbackRecommendation extends Model
{
     protected $table = 'feedback_recommendation';

    protected $fillable = [
       'recommendation_id',
        'rating',
        'is_relevant',
    ];

    public function recommendation()
{
    return $this->belongsTo(Recommendation::class, 'recommendation_id');
}

}
