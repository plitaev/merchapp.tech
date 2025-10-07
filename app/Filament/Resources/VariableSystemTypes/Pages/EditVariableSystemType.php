<?php

namespace App\Filament\Resources\VariableSystemTypes\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\VariableSystemTypes\VariableSystemTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVariableSystemType extends EditRecord
{
    protected static string $resource = VariableSystemTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
