<?php

namespace App\Filament\Resources\MiniAppBanners\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\MiniAppBanners\MiniAppBannerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMiniAppBanner extends EditRecord
{
    protected static string $resource = MiniAppBannerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
