<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Program;
use App\Models\Student;
use App\Models\Career;
use App\Models\Skill;
class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = [
       'name'
    ];

    public function students()
    {
        return $this->belongsToMany(Student::class, 'interests');
    }

    public function programs()
    {
        return $this->hasMany(Program::class, 'category_id');
    }

   public function careers()
{
    return $this->hasMany(Career::class, 'category_id');
}

    public function subcategories()
    {
        return $this->hasMany(SubCategory::class, 'category_id' );
    }
}