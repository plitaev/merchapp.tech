<?php

namespace App\Filament\Resources\VariableSystemTypeResource\Pages;

use App\Filament\Resources\VariableSystemTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVariableSystemTypes extends ListRecords
{
    protected static string $resource = VariableSystemTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
