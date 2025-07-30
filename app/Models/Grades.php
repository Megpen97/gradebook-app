<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grades extends Model
{
    protected $fillable = [
        'enrollment_id',
        'assignment_id',
        'score',
        'graded_on',
        'letter_grade',
        'comments'
    ];

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollments::class, 'enrollment_id', 'enrollment_id');
    }

    public function assignment(): BelongsTo 
    {
        return $this->belongsTo(Assignments::class, 'assignment_id', 'assignment_id');
    }
    
    
}
