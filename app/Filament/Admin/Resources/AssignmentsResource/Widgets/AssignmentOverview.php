<?php

namespace App\Filament\Admin\Resources\AssignmentsResource\Widgets;

use App\Models\Assignments;
use App\Models\Grades;
use App\Models\Enrollments;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AssignmentOverview extends BaseWidget
{
    public ?Assignments $record = null;

    private function getLetterGrade(float $score): string
    {
        if ($score >= 97) return 'A+';
        if ($score >= 93) return 'A';
        if ($score >= 90) return 'A-';
        if ($score >= 87) return 'B+';
        if ($score >= 83) return 'B';
        if ($score >= 80) return 'B-';
        if ($score >= 77) return 'C+';
        if ($score >= 73) return 'C';
        if ($score >= 70) return 'C-';
        if ($score >= 67) return 'D+';
        if ($score >= 63) return 'D';
        if ($score >= 60) return 'D-';
        return 'F';
    }

    private function getAverageColor(float $percentage): string
    {
        $letterGrade = $this->getLetterGrade($percentage);
        
        return match (true) {
            in_array($letterGrade, ['A+', 'A', 'A-']) => 'success',
            in_array($letterGrade, ['B+', 'B', 'B-']) => 'info', 
            in_array($letterGrade, ['C+', 'C', 'C-']) => 'warning',
            in_array($letterGrade, ['D+', 'D', 'D-']) => 'danger',
            $letterGrade === 'F' => 'danger',
            default => 'gray',
        };
    }

    private function getAssignmentAverageColor(): string
    {
        if (!$this->record) {
            return 'gray';
        }

        $avgScore = $this->record->grades()->avg('score');
        if (!$avgScore || $this->record->max_score == 0) {
            return 'gray';
        }
        
        $percentage = ($avgScore / $this->record->max_score) * 100;
        return $this->getAverageColor($percentage);
    }

    protected function getStats(): array
    {
        if (!$this->record) {
            return [];
        }

        return [
            // Total students enrolled in the course (potential submissions)
            Stat::make('Students in Course', function () {
                return Enrollments::where('course_id', $this->record->course_id)->count();
            })
                ->description('Total enrolled students')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),
                
            // Grades submitted for this assignment
            Stat::make('Grades Submitted', $this->record->grades()->count())
                ->description('Submissions graded')
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->color('success'),
                
            // Completion rate
            Stat::make('Completion Rate', function () {
                $totalStudents = Enrollments::where('course_id', $this->record->course_id)->count();
                $gradesSubmitted = $this->record->grades()->count();
                
                if ($totalStudents == 0) {
                    return '0%';
                }
                
                $completionRate = ($gradesSubmitted / $totalStudents) * 100;
                return number_format($completionRate, 1) . '%';
            })
                ->description('Students who submitted')
                ->descriptionIcon('heroicon-m-chart-pie')
                ->color('warning'),
                
            // Assignment average with letter grade
            Stat::make('Assignment Average', function () {
                $avgScore = $this->record->grades()->avg('score');
                
                if (!$avgScore) {
                    return 'No grades';
                }
                
                if ($this->record->max_score == 0) {
                    return 'Invalid max score';
                }
                
                $percentage = ($avgScore / $this->record->max_score) * 100;
                $letterGrade = $this->getLetterGrade($percentage);
                
                return number_format($percentage, 1) . '% (' . $letterGrade . ')';
            })
                ->description('Overall assignment performance')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color($this->getAssignmentAverageColor()),
        ];
    }
}