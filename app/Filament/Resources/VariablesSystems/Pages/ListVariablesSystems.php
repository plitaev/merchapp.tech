<?php

namespace App\Filament\Resources\VariablesSystems\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\VariablesSystems\VariablesSystemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVariablesSystems extends ListRecords
{
    protected static string $resource = VariablesSystemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
