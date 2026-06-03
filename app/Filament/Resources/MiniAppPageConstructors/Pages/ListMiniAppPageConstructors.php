<?php

namespace App\Filament\Resources\MiniAppPageConstructors\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\MiniAppPageConstructors\MiniAppPageConstructorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMiniAppPageConstructors extends ListRecords
{
    protected static string $resource = MiniAppPageConstructorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
