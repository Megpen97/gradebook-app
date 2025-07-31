<?php

namespace App\Filament\Admin\Resources\AssignmentsResource\Pages;

use App\Filament\Admin\Resources\AssignmentsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAssignments extends EditRecord
{
    protected static string $resource = AssignmentsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
