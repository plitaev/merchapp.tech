<?php

namespace App\Filament\Resources\MiniAppVideos\Pages;

use App\Filament\Resources\MiniAppVideos\MiniAppVideoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMiniAppVideos extends ListRecords
{
    protected static string $resource = MiniAppVideoResource::class;

    protected static bool $shouldRegisterNavigation = false;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
