<?php

namespace App\Actions\Core\TelegramSupergroup;

use Telegram\Bot\Api;

use App\Models\Core\TelegramSupergroup;

class TelegramSupergroupSendRecurrentFail
{
    public function handle($bot_user) {
        $telegram = new Api($bot_user->bot->telegram_token);

        $supergroups = TelegramSupergroup::select('telegram_id')->where('bot_id', $bot_user->bot_id)->where('statistic_recurrent_fail', 1)->get();
        foreach ($supergroups as $supergroup) {

            $text = "❗️ Не прошёл рекуррент%0A".$bot_user->email;
            if ($bot_user->username) $text = $text."%0A@".$bot_user->username;

            return $telegram->sendMessage(['chat_id' => $supergroup->telegram_id, 'parse_mode' => 'HTML', 'text' => urldecode($text), 'protect_content' => true]);
        }

    }
}
