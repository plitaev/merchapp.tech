<?php

namespace App\Filament\Resources\MiniAppPages\Pages;

use App\Filament\Resources\MiniAppPages\MiniAppPageResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMiniAppPage extends CreateRecord
{
    protected static string $resource = MiniAppPageResource::class;
}
