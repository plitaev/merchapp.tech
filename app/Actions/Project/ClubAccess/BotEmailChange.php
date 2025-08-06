<?php
namespace App\Actions\Project\ClubAccess;
use App\Actions\Project\ClubAccess\BotRequestEmail;

class BotEmailChange
{
    public function handle($telegram, $bot_user, $webhook) {
        $botRequestEmail = new BotRequestEmail();

        if (isset($webhook['callback_query']['message']['message_id'])) {
            $telegram->answerCallbackQuery(['callback_query_id' => $webhook['callback_query']['id']]);
            $botRequestEmail->handle($bot_user);
        }
    }
}
