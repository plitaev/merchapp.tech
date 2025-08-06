<?php

namespace App\Filament\Resources\ListenerResource\Pages;

use App\Filament\Resources\ListenerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListListeners extends ListRecords
{
    protected static string $resource = ListenerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
