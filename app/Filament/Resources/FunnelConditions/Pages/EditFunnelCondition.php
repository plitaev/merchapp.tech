<?php

namespace App\Filament\Resources\FunnelConditions\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\FunnelConditions\FunnelConditionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFunnelCondition extends EditRecord
{
    protected static string $resource = FunnelConditionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
