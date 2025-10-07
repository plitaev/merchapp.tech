<?php

namespace App\Filament\Resources\VariableSystemTypes\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\VariableSystemTypes\VariableSystemTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVariableSystemTypes extends ListRecords
{
    protected static string $resource = VariableSystemTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
