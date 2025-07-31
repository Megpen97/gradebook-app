<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grades extends Model
{
    use HasFactory;

    protected $fillable = [
        'enrollment_id',
        'assignment_id',
        'score',
        'letter_grade',
        'comments',
        'graded_on'
    ];

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollments::class, 'enrollment_id');
    }

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignments::class, 'assignment_id');
    }
}
