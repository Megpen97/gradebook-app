<?php

namespace App\Filament\Admin\Resources\CoursesResource\Pages;

use App\Filament\Admin\Resources\CoursesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCourses extends ListRecords
{
    protected static string $resource = CoursesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

}
