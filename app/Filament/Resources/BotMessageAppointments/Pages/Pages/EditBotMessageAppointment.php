<?php

namespace App\Filament\Resources\BotMessageAppointments\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\BotMessageAppointments\BotMessageAppointmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBotMessageAppointment extends EditRecord
{
    protected static string $resource = BotMessageAppointmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->visible(auth()->user()->can('Delete:BotMessageAppointment')),

        ];
    }
}
