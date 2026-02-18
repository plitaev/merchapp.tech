<?php

namespace App\Filament\Resources\MiniAppBlockTypes\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\MiniAppBlockTypes\MiniAppBlockTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMiniAppBlockType extends EditRecord
{
    protected static string $resource = MiniAppBlockTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
