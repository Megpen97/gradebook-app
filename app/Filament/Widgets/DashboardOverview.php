<?php

namespace App\Filament\Widgets;

use App\Models\Instructor;
use App\Models\Courses;
use App\Models\Student;
use App\Models\Enrollments;
use App\Models\Assignments;
use App\Models\Grades;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardOverview extends BaseWidget
{

    protected function getStats(): array
    {
        return [
            // Total instructors
            Stat::make('Total Instructors', Instructor::count())
                ->description('Active instructors')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),
                
            // Total courses
            Stat::make('Total Courses', Courses::count())
                ->description('All courses in system')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('info'),
                
            // Total students
            Stat::make('Total Students', Student::count())
                ->description('Registered students')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('warning'),
                
            // Total enrollments
            Stat::make('Total Enrollments', Enrollments::count())
                ->description('Active enrollments')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('primary'),
                
            // Total assignments
            Stat::make('Total Assignments', Assignments::count())
                ->description('All assignments created')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('secondary'),
        ];
    }
}