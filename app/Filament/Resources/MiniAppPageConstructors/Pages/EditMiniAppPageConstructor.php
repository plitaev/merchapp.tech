<?php

namespace App\Filament\Resources\MiniAppPageConstructors\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\MiniAppPageConstructors\MiniAppPageConstructorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMiniAppPageConstructor extends EditRecord
{
    protected static string $resource = MiniAppPageConstructorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
