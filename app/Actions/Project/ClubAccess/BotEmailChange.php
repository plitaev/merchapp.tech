<?php
namespace App\Actions\Project\ClubAccess;

use App\Actions\Core\Telegram\TelegramQuery;
use App\Actions\Project\ClubAccess\BotRequestEmail;

class BotEmailChange
{
    public function handle($messenger, $bot_user, $webhook) {

        $botRequestEmail = new BotRequestEmail();
        $telegramQuery = new TelegramQuery();
        $botRequestEmail->handle($bot_user);

        if ($messenger == 'telegram' && isset($webhook['callback_query']['message']['message_id'])) {
            $telegramQuery->handle($bot_user->bot, 'answerCallbackQuery', ['callback_query_id' => $webhook['callback_query']['id']]);
        }

    }
}
