<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollments extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'enrollment_date'
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'user_id', 'user_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Courses::class, 'course_id', 'course_id');
    }
    
    
}
