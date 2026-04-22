<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Program;

class Progrem_Requirement extends Model
{
    protected $table = 'program_requirments';
    protected $fillable = [
        'sat',
        'ielts',
        'toefl',
        'minimum_gpa'
    ];

    public function programs()
    {
        return $this->hasMany(Program::class, 'prog_requirement_id');
    }
}
