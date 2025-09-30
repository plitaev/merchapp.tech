<?php

namespace App\Actions\Core\Auto;

use Illuminate\Database\Eloquent\Relations\HasOneThrough;

use App\Actions\Core\BotSendMessage\BotSendMessage;
use App\Models\Core\TelegramSendMessageSchedule;

class TelegramSendMessageScheduleProcess
{
    public function handle() {
        $botSendMessage = new BotSendMessage();

        $res = TelegramSendMessageSchedule::with('bot_message')->with('bot_user')
            ->whereHas('sending', function ($query) {
                $query->where('send_datetime', '<=', date('Y-m-d H:i:s', time()));
            })
            ->where('run_status', 0)
            ->take(25)
            ->get();

        foreach ($res as $data) {
            TelegramSendMessageSchedule::where('id', $data->id)->update(['run_status' => 1]);
            $message = $botSendMessage->handle($data->bot_user, $data->bot_message->bot_message_appointment->alias);
            TelegramSendMessageSchedule::where('id', $data->id)->update(['message_id' => $message->message_id]);
        }

    }
}
