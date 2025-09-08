<?php

namespace App\Actions\Core\Auto;

use Illuminate\Database\Eloquent\Relations\HasOneThrough;

use App\Models\Core\TelegramSendMessageSchedule;

class TelegramSendMessageScheduleProcess
{
    public function handle() {
        $date = date('Y-m-d', time());
        $time = date('H:i:s', time());

        $res = TelegramSendMessageSchedule::with('bot_message,bot_message.bot_message_appointment')
            ->where('run_status', 0)
            ->get();

        return $res;

    }
}
