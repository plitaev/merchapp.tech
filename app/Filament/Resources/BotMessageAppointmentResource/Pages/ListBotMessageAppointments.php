<?php

namespace App\Filament\Resources\BotMessageAppointmentResource\Pages;

use App\Filament\Resources\BotMessageAppointmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBotMessageAppointments extends ListRecords
{
    protected static string $resource = BotMessageAppointmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
