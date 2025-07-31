<?php

namespace App\Filament\Admin\Resources\CoursesResource\Pages;

use App\Filament\Admin\Resources\CoursesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCourses extends EditRecord
{
    protected static string $resource = CoursesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
