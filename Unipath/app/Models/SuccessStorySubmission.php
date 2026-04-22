<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuccessStorySubmission extends Model
{
    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'story',
        'status',
    ];
}