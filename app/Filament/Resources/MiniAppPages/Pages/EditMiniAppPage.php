<?php

namespace App\Filament\Resources\MiniAppPages\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\MiniAppPages\MiniAppPageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMiniAppPage extends EditRecord
{
    protected static string $resource = MiniAppPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
