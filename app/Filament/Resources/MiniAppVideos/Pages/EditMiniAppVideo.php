<?php

namespace App\Filament\Resources\MiniAppVideos\Pages;

use App\Filament\Resources\MiniAppVideos\MiniAppVideoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMiniAppVideo extends EditRecord
{
    protected static string $resource = MiniAppVideoResource::class;

    protected static bool $shouldRegisterNavigation = false;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
