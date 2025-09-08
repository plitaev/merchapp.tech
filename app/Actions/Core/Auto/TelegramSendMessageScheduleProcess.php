<?php

namespace App\Actions\Core\Auto;

use Illuminate\Database\Eloquent\Relations\HasOneThrough;

use App\Actions\Core\BotSendMessage\BotSendMessage;
use App\Models\Core\TelegramSendMessageSchedule;

class TelegramSendMessageScheduleProcess
{
    public function handle() {
        $botSendMessage = new BotSendMessage();

        $date = date('Y-m-d', time());
        $time = date('H:i:s', time());

        $res = TelegramSendMessageSchedule::with('bot_message,bot_user')
            ->where('run_status', 0)
            ->get();

        return $res;

        foreach ($res as $data) {
            return $botSendMessage->handle();
        }

    }
}
