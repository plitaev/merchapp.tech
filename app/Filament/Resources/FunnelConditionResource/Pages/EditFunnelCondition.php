<?php

namespace App\Filament\Resources\FunnelConditionResource\Pages;

use App\Filament\Resources\FunnelConditionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFunnelCondition extends EditRecord
{
    protected static string $resource = FunnelConditionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
