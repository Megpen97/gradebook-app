<?php

namespace App\Filament\Admin\Resources\InstructorResource\Widgets;

use App\Models\Instructor;
use App\Models\Courses;
use App\Models\Enrollments;
use App\Models\Assignments;
use App\Models\Grades;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InstructorOverview extends BaseWidget
{
    public ?Instructor $record = null;

    protected function getStats(): array
    {
        if (!$this->record) {
            return [];
        }

        return [
            // Number of courses teaching
            Stat::make('Courses Teaching', $this->record->courses()->count())
                ->description('Active courses')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('success'),
                
            // Total students across all courses
            Stat::make('Total Students', function () {
                return Enrollments::whereIn('course_id', $this->record->courses()->pluck('id'))->count();
            })
                ->description('Students across all courses')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),
                
            // Total assignments created
            Stat::make('Assignments Created', function () {
                return Assignments::whereIn('course_id', $this->record->courses()->pluck('id'))->count();
            })
                ->description('Total assignments')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('warning'),
        ];
    }
}