<?php

namespace App\Filament\Resources\Listeners\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Listeners\ListenerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListListeners extends ListRecords
{
    protected static string $resource = ListenerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
