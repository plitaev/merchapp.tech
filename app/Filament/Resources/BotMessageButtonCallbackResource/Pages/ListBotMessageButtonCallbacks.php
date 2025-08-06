<?php

namespace App\Filament\Resources\BotMessageButtonCallbackResource\Pages;

use App\Filament\Resources\BotMessageButtonCallbackResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBotMessageButtonCallbacks extends ListRecords
{
    protected static string $resource = BotMessageButtonCallbackResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
