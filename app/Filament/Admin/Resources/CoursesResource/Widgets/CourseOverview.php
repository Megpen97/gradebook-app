<?php

namespace App\Filament\Admin\Resources\CoursesResource\Widgets;

use App\Models\Courses;
use App\Models\Enrollments;
use App\Models\Assignments;
use App\Models\Grades;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CourseOverview extends BaseWidget
{
    public ?Courses $record = null;

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

    private function getCourseAverageColor(): string
    {
        $avgScore = Grades::whereHas('assignment', function ($query) {
            $query->where('course_id', $this->record->id);
        })->avg('score');
        
        if (!$avgScore) return 'gray';
        
        $letterGrade = $this->getLetterGrade($avgScore);
        
        return match (true) {
            in_array($letterGrade, ['A+', 'A', 'A-']) => 'success',
            in_array($letterGrade, ['B+', 'B', 'B-']) => 'info', 
            in_array($letterGrade, ['C+', 'C', 'C-']) => 'warning',
            in_array($letterGrade, ['D+', 'D', 'D-']) => 'danger',
            $letterGrade === 'F' => 'danger',
            default => 'gray',
        };
    }

    protected function getStats(): array
    {
        // If no record (dashboard context), show overall stats
        if (!$this->record) {
            return [
                Stat::make('Total Courses', Courses::count())
                    ->description('All courses in system')
                    ->descriptionIcon('heroicon-m-book-open')
                    ->color('success'),
                    
                Stat::make('Total Students', Enrollments::distinct('student_id')->count())
                    ->description('Students enrolled')
                    ->descriptionIcon('heroicon-m-academic-cap')
                    ->color('info'),
                    
                Stat::make('Total Assignments', Assignments::count())
                    ->description('All assignments created')
                    ->descriptionIcon('heroicon-m-clipboard-document-list')
                    ->color('warning'),
                    
                Stat::make('Overall Average', function () {
                    $avgScore = Grades::avg('score');
                    if (!$avgScore) {
                        return 'No grades';
                    }
                    $letterGrade = $this->getLetterGrade($avgScore);
                    return number_format($avgScore, 1) . '% (' . $letterGrade . ')';
                })
                    ->description('System-wide performance')
                    ->descriptionIcon('heroicon-m-chart-bar')
                    ->color($this->getOverallAverageColor()),
            ];
        }

        // Course-specific stats (when viewing a specific course)
        return [
            // Total students enrolled in this specific course
            Stat::make('Students Enrolled', Enrollments::where('course_id', $this->record->id)->count())
                ->description('Students in this course')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),
                
            // Total assignments for this course
            Stat::make('Assignments', Assignments::where('course_id', $this->record->id)->count())
                ->description('Assignments created')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('info'),
                
            // Average grade for this course
            Stat::make('Course Average', function () {
                $avgScore = Grades::whereHas('assignment', function ($query) {
                    $query->where('course_id', $this->record->id);
                })->avg('score');
                
                if (!$avgScore) {
                    return 'No grades';
                }
                
                $letterGrade = $this->getLetterGrade($avgScore);
                return number_format($avgScore, 1) . '% (' . $letterGrade . ')';
            })
                ->description('Overall course performance')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color($this->getCourseAverageColor()),
        ];
    }

    private function getOverallAverageColor(): string
    {
        $avgScore = Grades::avg('score');
        
        if (!$avgScore) return 'gray';
        
        $letterGrade = $this->getLetterGrade($avgScore);
        
        return match (true) {
            in_array($letterGrade, ['A+', 'A', 'A-']) => 'success',
            in_array($letterGrade, ['B+', 'B', 'B-']) => 'info', 
            in_array($letterGrade, ['C+', 'C', 'C-']) => 'warning',
            in_array($letterGrade, ['D+', 'D', 'D-']) => 'danger',
            $letterGrade === 'F' => 'danger',
            default => 'gray',
        };
    }
}
