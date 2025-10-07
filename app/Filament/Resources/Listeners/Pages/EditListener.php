<?php

namespace App\Filament\Resources\Listeners\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\Listeners\ListenerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditListener extends EditRecord
{
    protected static string $resource = ListenerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
