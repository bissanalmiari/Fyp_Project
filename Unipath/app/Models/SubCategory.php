<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Student;

class SubCategory extends Model
{
    protected $table = 'subcategories';
    protected $fillable = [
        'name',
        'category_id'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_subcategory','student_id' ,'subcategory_id');
    }

    public function programs()
    {
        return $this->hasMany(Program::class, 'subcategory_id');
    }
}