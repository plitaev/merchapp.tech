<?php
namespace App\Actions\Core\BotUser;

use App\Actions\Core\BotSendMessage\BotSendMessage;
use App\Models\Core\BotUser;

class BotUserSetEmail
{
    public function handle($bot_user, string $email) {
        $botSendMessage = new BotSendMessage();

        $other_telegram_user = BotUser::where('email', $email)->whereNot('telegram_chat_id', $bot_user->telegram_chat_id)->count();

        if ($other_telegram_user > 0) {
            $botSendMessage->handle($bot_user, 'SYS_OTHER_USER_WITH_ENTERED_EMAIL');
            die();
        } else {
            BotUser::where('id', $bot_user->id)->update(['email' => $email]);
        }
    }
}
