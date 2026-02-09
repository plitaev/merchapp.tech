<?php

namespace App\Filament\Resources\MiniAppVideos\Pages;

use App\Filament\Resources\MiniAppVideos\MiniAppVideoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMiniAppVideo extends CreateRecord
{
    protected static string $resource = MiniAppVideoResource::class;

    protected static bool $shouldRegisterNavigation = false;

    protected function getFormActions(): array {
        return [];
    }

}
