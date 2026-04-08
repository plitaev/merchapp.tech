<?php

namespace App\Actions\Project\ClubAccess;

use App\Actions\Core\BotSendMessage\BotSendMessage;

class BotMainMenuMessage
{
    public function handle($messenger, $telegram, $webhook, $bot_user) {
        $botSendMessage = new BotSendMessage();
        $botSendMessage->handle($bot_user, 'SYS_MAIN_MENU_MESSAGE');

        if ($messenger == 'telegram' && isset($webhook['callback_query']['message']['message_id'])) {
            $telegram->answerCallbackQuery(['callback_query_id' => $webhook['callback_query']['id']]);
        }

    }
}
