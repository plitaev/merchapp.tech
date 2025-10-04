<?php

namespace App\Actions\Core\BotSendingAdmin;

use Filament\Notifications\Notification;
use Tables\Actions\DeleteAction;

use App\Models\Core\TelegramSendMessageSchedule;

class BotSendingAdminDeleteRecord
{
    public function handle($record, $action) {
        $check = TelegramSendMessageSchedule::where('sending_id', $record->id)->where('run_status', '>', 0)->count();
        if ($check) {
            TelegramSendMessageSchedule::where('sending_id', $record->id)->update(['run_status' => 3]);

            Notification::make()
                ->title('Рассылка уже доставлена пользователям, удаление невозможно. Неотправленные сообщения будут отменены.')
                ->danger()
                ->send();

            $action->cancel();
        }
    }
}
