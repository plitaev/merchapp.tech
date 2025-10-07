<?php

namespace App\Filament\Resources\FunnelMessages\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\FunnelMessages\FunnelMessageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFunnelMessage extends EditRecord
{
    protected static string $resource = FunnelMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
