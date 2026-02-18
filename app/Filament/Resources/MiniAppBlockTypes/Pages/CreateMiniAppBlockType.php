<?php

namespace App\Filament\Resources\MiniAppBlockTypes\Pages;

use App\Filament\Resources\MiniAppBlockTypes\MiniAppBlockTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMiniAppBlockType extends CreateRecord
{
    protected static string $resource = MiniAppBlockTypeResource::class;
}
