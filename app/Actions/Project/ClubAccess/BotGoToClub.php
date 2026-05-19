<?php
namespace App\Actions\Project\ClubAccess;

use App\Actions\Core\BotUser\BotUserSetListener;
use App\Actions\Core\Telegram\TelegramQuery;

class BotGoToClub
{
    public function handle(string $messenger, $webhook, $bot_user) {
        $botRequestAndConfirmEmail = new BotRequestAndConfirmEmail();
        $botUserSetListener = new BotUserSetListener();
        $telegramQuery = new TelegramQuery();

        if ($messenger == 'telegram' && isset($webhook['callback_query']['message']['message_id'])) {
            $telegramQuery->handle($bot_user->bot, 'answerCallbackQuery', ['callback_query_id' => $webhook['callback_query']['id']]);
        }

        $botUserSetListener->handle('sys_go_to_pay', 1, $bot_user->id);
        $botRequestAndConfirmEmail->handle($bot_user);
    }
}
