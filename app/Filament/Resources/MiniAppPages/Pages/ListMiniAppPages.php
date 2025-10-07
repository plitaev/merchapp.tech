<?php

namespace App\Filament\Resources\MiniAppPages\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\MiniAppPages\MiniAppPageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMiniAppPages extends ListRecords
{
    protected static string $resource = MiniAppPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
