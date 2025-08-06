<?php

namespace App\Filament\Resources\VariableGroupResource\Pages;

use App\Filament\Resources\VariableGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVariableGroups extends ListRecords
{
    protected static string $resource = VariableGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
