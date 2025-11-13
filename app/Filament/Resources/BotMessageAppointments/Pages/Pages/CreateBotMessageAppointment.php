<?php

namespace App\Filament\Resources\BotMessageAppointments\Pages;

use App\Filament\Resources\BotMessageAppointments\BotMessageAppointmentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBotMessageAppointment extends CreateRecord
{
    protected static string $resource = BotMessageAppointmentResource::class;
}
