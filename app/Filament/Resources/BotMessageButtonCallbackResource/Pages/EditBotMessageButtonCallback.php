<?php

namespace App\Filament\Resources\BotMessageButtonCallbackResource\Pages;

use App\Filament\Resources\BotMessageButtonCallbackResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBotMessageButtonCallback extends EditRecord
{
    protected static string $resource = BotMessageButtonCallbackResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
