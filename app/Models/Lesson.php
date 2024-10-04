<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use function Illuminate\Support\Facades\Date;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'order',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public $attributes = [
        'title' => '',
        'order' => 0,
    ];
}
