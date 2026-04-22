<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category; 
use App\Models\CareerMajor; 
class Career extends Model
{
     protected $table = 'careers';
    protected $fillable = [
       'category_id', 
       'title', 
       'description', 
       'image_path', 
       'is_active', 
       'in_demand', 
       'min_salary', 
       'max_salary'
    ];

    public function category()
{
    return $this->belongsTo(Category::class, 'category_id');
}


}
