<?php

namespace App\Actions\Core\BotSendMessage;

use App\Models\Core\BotMessage;
use App\Models\Core\BotMessageAppointment;

class BotSendMessageAlert
{
    public function handle($bot_id, $telegram, $webhook, $alias) {
        if (isset($webhook['callback_query']['message']['message_id'])) {
            $bot_message_appointment = BotMessageAppointment::where('alias', $alias)->first();
            if ($bot_message_appointment) {
                $bot_message = BotMessage::select('text')->where('bot_id', $bot_id)->where('bot_message_appointment_id', $bot_message_appointment->id)->first();
            }

            $telegram->answerCallbackQuery(['callback_query_id' => $webhook['callback_query']['id'], 'show_alert' => true, 'text' => urldecode($bot_message->text)]);
        }
    }
}
