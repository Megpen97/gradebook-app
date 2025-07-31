<?php

namespace App\Filament\Admin\Resources\GradesResource\Pages;

use App\Filament\Admin\Resources\GradesResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewGrades extends ViewRecord
{
    protected static string $resource = GradesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
} 