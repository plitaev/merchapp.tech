<?php

namespace App\Filament\Resources\MiniAppBannerResource\Pages;

use App\Filament\Resources\MiniAppBannerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMiniAppBanner extends EditRecord
{
    protected static string $resource = MiniAppBannerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
