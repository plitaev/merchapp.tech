<?php

namespace App\Filament\Resources\VariableGroups\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\VariableGroups\VariableGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVariableGroups extends ListRecords
{
    protected static string $resource = VariableGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
