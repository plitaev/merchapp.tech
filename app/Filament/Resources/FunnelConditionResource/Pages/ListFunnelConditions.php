<?php

namespace App\Filament\Resources\FunnelConditionResource\Pages;

use App\Filament\Resources\FunnelConditionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFunnelConditions extends ListRecords
{
    protected static string $resource = FunnelConditionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
