<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Student;

class SuccessStory extends Model
{
    protected $table = 'success_stories';

    protected $fillable = [
        'student_id',
        'full_name',
        'email',
        'phone',
        'story_text',
        'profile_image',
        'status',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
