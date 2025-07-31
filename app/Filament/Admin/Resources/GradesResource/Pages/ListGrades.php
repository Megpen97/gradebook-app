<?php

namespace App\Filament\Admin\Resources\GradesResource\Pages;

use App\Filament\Admin\Resources\GradesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGrades extends ListRecords
{
    protected static string $resource = GradesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
