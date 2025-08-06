<?php

namespace App\Filament\Resources\MiniAppResource\Pages;

use App\Filament\Resources\MiniAppResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMiniApp extends EditRecord
{
    protected static string $resource = MiniAppResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
