<?php
namespace App\Actions\Project\ClubAccess;

use App\Actions\Core\BotSendMessage\BotSendMessage;
use App\Actions\Core\Telegram\TelegramQuery;

use App\Actions\Project\ClubAccess\BotMainMenuMessage;

class BotEmailVerification
{
    public function handle(string $messenger, $bot_user, $webhook) {

        $botMainMenuMessage = new BotMainMenuMessage();
        $botSendMessage = new BotSendMessage();
        $telegramQuery = new TelegramQuery();

        if ($messenger == 'telegram' && isset($webhook['callback_query']['message']['message_id'])) {
            $telegramQuery->handle($bot_user->bot, 'answerCallbackQuery', ['callback_query_id' => $webhook['callback_query']['id']]);
        }

        if ($bot_user->sys_go_to_pay_status == 1) {
            $botSendMessage->handle($bot_user, 'SYS_PAY_IN_BOT');
            die();
        } else {
            $botMainMenuMessage->handle($messenger, $webhook, $bot_user);
        }

    }
}
