<?php

namespace App\Actions\Project\ClubAccess;

use App\Actions\Core\BotSendMessage\BotSendMessage;
use App\Actions\Core\Telegram\TelegramQuery;

class BotMainMenuMessage
{
    public function handle($messenger, $webhook, $bot_user) {
        $botSendMessage = new BotSendMessage();
        $botSendMessage->handle($bot_user, 'SYS_MAIN_MENU_MESSAGE');
        $telegramQuery = new TelegramQuery();

        if ($messenger == 'telegram' && isset($webhook['callback_query']['message']['message_id'])) {
            $telegramQuery->handle($bot_user->bot, 'answerCallbackQuery', ['callback_query_id' => $webhook['callback_query']['id']]);
        }

    }
}
