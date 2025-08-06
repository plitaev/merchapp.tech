<?php

namespace App\Filament\Resources\FunnelMessageResource\Pages;

use App\Filament\Resources\FunnelMessageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFunnelMessage extends EditRecord
{
    protected static string $resource = FunnelMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
