<?php

namespace App\Filament\Admin\Resources\EnrollmentsResource\Pages;

use App\Filament\Admin\Resources\EnrollmentsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEnrollments extends EditRecord
{
    protected static string $resource = EnrollmentsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
