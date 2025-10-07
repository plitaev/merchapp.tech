<?php

namespace App\Filament\Resources\Listeners\Pages;

use App\Filament\Resources\Listeners\ListenerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateListener extends CreateRecord
{
    protected static string $resource = ListenerResource::class;
}
