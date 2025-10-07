<?php

namespace App\Filament\Resources\FunnelMessages\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\FunnelMessages\FunnelMessageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFunnelMessages extends ListRecords
{
    protected static string $resource = FunnelMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
