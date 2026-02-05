<?php

namespace App\Actions\Core\MiniAppVideo;

use Filament\Notifications\Notification;

use App\Models\Core\MiniAppVideoTimePoint;

class MiniAppVideoTimePointAdminDeleteRecord
{
    public function handle($record, $action) {
        MiniAppVideoTimePoint::where('id', $record->id)->delete();

    }
}
