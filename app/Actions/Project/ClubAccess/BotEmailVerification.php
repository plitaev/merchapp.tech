<?php
namespace App\Actions\Project\ClubAccess;

use App\Actions\Core\BotSendMessage\BotSendMessage;
use App\Actions\Project\ClubAccess\BotMainMenuMessage;

class BotEmailVerification
{
    public function handle($telegram, $bot_user, $webhook) {

        $botMainMenuMessage = new BotMainMenuMessage();
        $botSendMessage = new BotSendMessage();

        if (isset($webhook['callback_query']['message']['message_id'])) {
            $telegram->answerCallbackQuery(['callback_query_id' => $webhook['callback_query']['id']]);

            if ($bot_user->sys_go_to_pay_status == 1) {
                return 'aa';
                return $botSendMessage->handle($bot_user, 'SYS_PAY_IN_BOT');
                die();
            } else {
                $botMainMenuMessage->handle($bot_user);
            }

        }
    }
}
