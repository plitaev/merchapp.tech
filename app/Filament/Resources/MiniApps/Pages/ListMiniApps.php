<?php

namespace App\Filament\Resources\MiniApps\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\MiniApps\MiniAppResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMiniApps extends ListRecords
{
    protected static string $resource = MiniAppResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
