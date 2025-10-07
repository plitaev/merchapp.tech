<?php

namespace App\Filament\Resources\MiniAppBanners\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\MiniAppBanners\MiniAppBannerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMiniAppBanners extends ListRecords
{
    protected static string $resource = MiniAppBannerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
