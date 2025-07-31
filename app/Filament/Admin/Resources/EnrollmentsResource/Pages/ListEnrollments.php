<?php

namespace App\Filament\Admin\Resources\EnrollmentsResource\Pages;

use App\Filament\Admin\Resources\EnrollmentsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEnrollments extends ListRecords
{
    protected static string $resource = EnrollmentsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
