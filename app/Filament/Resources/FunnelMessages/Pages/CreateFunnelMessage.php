<?php

namespace App\Filament\Resources\FunnelMessages\Pages;

use App\Filament\Resources\FunnelMessages\FunnelMessageResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFunnelMessage extends CreateRecord
{
    protected static string $resource = FunnelMessageResource::class;
}
