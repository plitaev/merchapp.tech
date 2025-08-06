<?php

namespace App\Filament\Resources\VariablesSystemResource\Pages;

use App\Filament\Resources\VariablesSystemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVariablesSystems extends ListRecords
{
    protected static string $resource = VariablesSystemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
