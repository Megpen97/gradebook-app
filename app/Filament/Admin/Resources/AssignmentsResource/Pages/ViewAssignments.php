<?php

namespace App\Filament\Admin\Resources\AssignmentsResource\Pages;

use App\Filament\Admin\Resources\AssignmentsResource;
use App\Filament\Admin\Resources\AssignmentsResource\Widgets\AssignmentOverview;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAssignments extends ViewRecord
{
    protected static string $resource = AssignmentsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            AssignmentOverview::class,
        ];
    }
}  