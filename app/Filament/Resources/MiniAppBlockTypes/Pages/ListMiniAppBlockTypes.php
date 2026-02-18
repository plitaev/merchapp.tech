<?php

namespace App\Filament\Resources\MiniAppBlockTypes\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\MiniAppBlockTypes\MiniAppBlockTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMiniAppBlockTypes extends ListRecords
{
    protected static string $resource = MiniAppBlockTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
