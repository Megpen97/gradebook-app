<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assignments extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'course_id',
        'due_date',
        'max_score'
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Courses::class);
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grades::class, 'assignment_id');
    }

    // Add accessor for average score
    public function getAverageScoreAttribute(): float
    {
        return $this->grades()->avg('score') ?? 0;
    }

    // Add accessor for grade count
    public function getGradeCountAttribute(): int
    {
        return $this->grades()->count();
    }

    // Add method to calculate letter grade from percentage
    public function calculateLetterGrade(float $percentage): string
    {
        return match (true) {
            $percentage >= 97 => 'A+',
            $percentage >= 93 => 'A',
            $percentage >= 90 => 'A-',
            $percentage >= 87 => 'B+',
            $percentage >= 83 => 'B',
            $percentage >= 80 => 'B-',
            $percentage >= 77 => 'C+',
            $percentage >= 73 => 'C',
            $percentage >= 70 => 'C-',
            $percentage >= 67 => 'D+',
            $percentage >= 63 => 'D',
            $percentage >= 60 => 'D-',
            default => 'F',
        };
    }

    // Add accessor for average letter grade
    public function getAverageLetterGradeAttribute(): string
    {
        $avgScore = $this->average_score;
        if ($avgScore == 0 || $this->max_score == 0) {
            return 'N/A';
        }
        
        $percentage = ($avgScore / $this->max_score) * 100;
        return $this->calculateLetterGrade($percentage);
    }

    // Add accessor for formatted average with letter grade
    public function getFormattedAverageAttribute(): string
    {
        $avgScore = $this->average_score;
        if ($avgScore == 0) {
            return 'No grades';
        }
        
        return number_format($avgScore, 1) . ' (' . $this->average_letter_grade . ')';
    }
}
