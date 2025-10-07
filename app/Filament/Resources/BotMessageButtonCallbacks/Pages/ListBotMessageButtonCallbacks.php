<?php

namespace App\Filament\Resources\BotMessageButtonCallbacks\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\BotMessageButtonCallbacks\BotMessageButtonCallbackResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBotMessageButtonCallbacks extends ListRecords
{
    protected static string $resource = BotMessageButtonCallbackResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
