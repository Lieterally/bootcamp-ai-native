<?php

namespace App\Filament\Resources\PeriodeAkademikResource\Pages;

use App\Filament\Resources\PeriodeAkademikResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPeriodeAkademiks extends ListRecords
{
    protected static string $resource = PeriodeAkademikResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
