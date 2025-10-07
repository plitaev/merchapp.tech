<?php

namespace App\Filament\Resources\VariableGroups\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\VariableGroups\VariableGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVariableGroup extends EditRecord
{
    protected static string $resource = VariableGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
