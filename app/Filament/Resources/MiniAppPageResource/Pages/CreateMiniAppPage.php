<?php

namespace App\Filament\Resources\MiniAppPageResource\Pages;

use App\Filament\Resources\MiniAppPageResource;
use App\Models\Core\MiniAppPage;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMiniAppPage extends CreateRecord
{
    protected static string $resource = MiniAppPageResource::class;

    public function mount(): void
    {
        $data['url'] = md5(time());
        $this->form->fill($data);
    }
}
