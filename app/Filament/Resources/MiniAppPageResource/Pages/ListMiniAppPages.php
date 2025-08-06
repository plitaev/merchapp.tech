<?php

namespace App\Filament\Resources\MiniAppPageResource\Pages;

use App\Filament\Resources\MiniAppPageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMiniAppPages extends ListRecords
{
    protected static string $resource = MiniAppPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
