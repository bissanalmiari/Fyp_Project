<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Program;

class University extends Model
{
     protected $table = 'universities';
     protected $fillable = [
        'name',
        'country',
        'city',
        'rank',
        'logo',
        'website_url',
        'image',
        'backup_image',
        'description',
        'type',
        'insta',
        'linkedin',
        'facebook'
     ];

    public function programs()
    {
        return $this->hasMany(Program::class, 'university_id');
    }
}
