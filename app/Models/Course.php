<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'intro_url',
        'image',
        'price',
        'price_sale',
        'level',
        'status',
        'requirements',
        'benefits',
        'qa',
    ];

    public $attributes = [
        'price' => 0,
        'price_sale' => 0,
        'requirements' => [],
        'benefits' => [],
        'qa' => [],
    ];

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
}
