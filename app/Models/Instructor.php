<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address', 
        'city',
        'state',
        'zip',
        'date_of_birth',
        'hire_date'
    ];

    public function courses(): HasMany
    {
        return $this->hasMany(Courses::class, 'instructor_id', 'instructor_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

}
