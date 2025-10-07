<?php

namespace App\Filament\Resources\VariablesSystems\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\VariablesSystems\VariablesSystemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVariablesSystem extends EditRecord
{
    protected static string $resource = VariablesSystemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
