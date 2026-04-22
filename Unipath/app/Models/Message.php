<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Student;

class Message extends Model
{
    protected $table = 'messages';
    protected $fillable = [
        'student_id',
        'message'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
