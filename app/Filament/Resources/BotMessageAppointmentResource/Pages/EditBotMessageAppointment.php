<?php

namespace App\Filament\Resources\BotMessageAppointmentResource\Pages;

use App\Filament\Resources\BotMessageAppointmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBotMessageAppointment extends EditRecord
{
    protected static string $resource = BotMessageAppointmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
