<?php

namespace App\Actions\Core\Auto;

use App\Models\Core\TelegramSendMessageSchedule;

class TelegramSendMessageScheduleProcess
{
    public function handle() {
        $date = date('Y-m-d', time());
        $time = date('H:i:s', time());

        $res = TelegramSendMessageSchedule::query()
            ->join('sendings', 'sendings.id', '=', 'telegram_send_message_schedules.telegram_send_message_schedules')
            ->join('bot_messages', 'bot_messages.id', '=', 'telegram_send_message_schedules.telegram_send_message_schedules')

    }
}
