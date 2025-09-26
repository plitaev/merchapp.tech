<?php

namespace App\Actions\Core\TelegramSupergroup;

use Telegram\Bot\Api;

use App\Actions\Core\BotUser\BotUserInsertVariables;

use App\Models\Core\BotMessage;
use App\Models\Core\BotMessageAppointment;
use App\Models\Core\TelegramSupergroup;

class TelegramSupergroupSendRecurrentFail
{
    public function handle($bot_user) {
        $botUserInsertVariables = new BotUserInsertVariables();

        $bot_message_appointment = BotMessageAppointment::where('alias', 'SYS_SG_NOTIFICATION_RECURRENT_FAIL')->first();

        if ($bot_message_appointment) {
            $bot_message = BotMessage::select('text')->where('bot_id', $bot_user->bot_id)->where('bot_message_appointment_id', $bot_message_appointment->id)->first();

            if ($bot_message) {

                $text = $bot_message->text;
                $text = urldecode($text);
                $text = $botUserInsertVariables->handle($bot_user, $text);

                $telegram = new Api($bot_user->bot->telegram_token);

                $supergroups = TelegramSupergroup::select('telegram_id')->where('bot_id', $bot_user->bot_id)->where('statistic_recurrent_fail', 1)->get();
                foreach ($supergroups as $supergroup) {
                    $telegram->sendMessage(['chat_id' => $supergroup->telegram_id, 'parse_mode' => 'HTML', 'text' => urldecode($text), 'protect_content' => true]);
                }
            }

        }

    }
}
