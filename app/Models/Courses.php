<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Courses extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_name',
        'course_code',
        'instructor_id'
    ];
    
    public function instructor(): BelongsTo
    {
        return $this->belongsTo(Instructor::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignments::class, 'course_id');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollments::class, 'course_id');
    }
}
