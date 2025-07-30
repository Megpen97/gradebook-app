<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignments extends Model
{
    protected $fillable = [
        'assignment_id',
        'course_id',
        'due_date',
        'max_score'
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Courses::class, 'course_id', 'course_id');
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grades::class, 'assignment_id', 'assignment_id');
    }
    
}
