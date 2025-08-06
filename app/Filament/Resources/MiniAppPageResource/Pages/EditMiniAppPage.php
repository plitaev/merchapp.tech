<?php

namespace App\Filament\Resources\MiniAppPageResource\Pages;

use App\Filament\Resources\MiniAppPageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMiniAppPage extends EditRecord
{
    protected static string $resource = MiniAppPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
