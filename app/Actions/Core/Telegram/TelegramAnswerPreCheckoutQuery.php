<?php
namespace App\Actions\Core\Telegram;
use Telegram\Bot\Api;

use App\Actions\Core\Bot\BotGetByID;

class TelegramAnswerPreCheckoutQuery
{
    public function handle($bot_id, $json) {
        $botGetByID = new BotGetByID();
        $bot = $botGetByID->handle($bot_id);

        $telegram = new Api($bot->telegram_token);
        $telegram->answerPreCheckoutQuery(['pre_checkout_query_id' => $json['pre_checkout_query']['id'], 'ok' => true]);
    }
}
