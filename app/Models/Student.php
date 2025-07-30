<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'zip',
        'date_of_birth',
        'enrollment_date'
    ];

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'user_id', 'user_id');
    }
    
}
