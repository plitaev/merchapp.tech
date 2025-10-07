<?php

namespace App\Filament\Resources\BotMessageAppointments\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\BotMessageAppointments\BotMessageAppointmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBotMessageAppointments extends ListRecords
{
    protected static string $resource = BotMessageAppointmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
