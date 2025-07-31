<?php

namespace App\Filament\Admin\Resources\CoursesResource\Pages;

use App\Filament\Admin\Resources\CoursesResource;
use App\Filament\Admin\Resources\CoursesResource\Widgets\CourseOverview;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCourses extends ViewRecord
{
    protected static string $resource = CoursesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            CourseOverview::class,
        ];
    }
} 