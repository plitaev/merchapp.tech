<?php

namespace App\Filament\Resources\FunnelMessageResource\Pages;

use App\Filament\Resources\FunnelMessageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFunnelMessages extends ListRecords
{
    protected static string $resource = FunnelMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
