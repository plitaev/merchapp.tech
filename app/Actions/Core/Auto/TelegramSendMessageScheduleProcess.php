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

        $res = TelegramSendMessageSchedule::with('bot_message')->with('bot_user')
            ->where('run_status', 0)
            ->get();

        foreach ($res as $data) {
            return $data->bot_message->bot_message_appointment;
            return $botSendMessage->handle($data->bot_user, $data->bot_message->bot_message_appointment);
        }

    }
}
