<?php

namespace App\Filament\Resources\VariablesSystemResource\Pages;

use App\Filament\Resources\VariablesSystemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVariablesSystem extends EditRecord
{
    protected static string $resource = VariablesSystemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
