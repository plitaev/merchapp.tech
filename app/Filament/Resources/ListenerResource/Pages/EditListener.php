<?php

namespace App\Filament\Resources\ListenerResource\Pages;

use App\Filament\Resources\ListenerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditListener extends EditRecord
{
    protected static string $resource = ListenerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
