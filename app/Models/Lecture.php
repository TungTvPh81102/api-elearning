<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecture extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'title',
        'type',
        'content',
        'order',
        'slug',
        'playback_id',
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
