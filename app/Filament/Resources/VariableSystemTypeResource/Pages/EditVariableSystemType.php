<?php

namespace App\Filament\Resources\VariableSystemTypeResource\Pages;

use App\Filament\Resources\VariableSystemTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVariableSystemType extends EditRecord
{
    protected static string $resource = VariableSystemTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
