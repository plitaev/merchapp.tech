<?php

namespace App\Filament\Resources\FunnelConditions\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\FunnelConditions\FunnelConditionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFunnelConditions extends ListRecords
{
    protected static string $resource = FunnelConditionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
