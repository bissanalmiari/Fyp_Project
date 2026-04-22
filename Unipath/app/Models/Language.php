<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Program;
use App\Models\Student;

class Language extends Model
{
    protected $table = 'languages';
    protected $fillable = [
        'name'
    ];

    public function programs()
{
    return $this->belongsToMany(Program::class, 'language_program', 'language_id', 'program_id');
}
    public function students()
    {
        return $this->belongsToMany(Student::class, 'language_student');
    }
}