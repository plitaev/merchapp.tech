<?php

namespace App\Filament\Resources\BotMessageButtonCallbacks\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\BotMessageButtonCallbacks\BotMessageButtonCallbackResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBotMessageButtonCallback extends EditRecord
{
    protected static string $resource = BotMessageButtonCallbackResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
