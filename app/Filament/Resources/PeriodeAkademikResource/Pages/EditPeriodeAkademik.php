<?php

namespace App\Filament\Resources\PeriodeAkademikResource\Pages;

use App\Filament\Resources\PeriodeAkademikResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPeriodeAkademik extends EditRecord
{
    protected static string $resource = PeriodeAkademikResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
