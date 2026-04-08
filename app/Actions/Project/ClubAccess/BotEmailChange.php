<?php
namespace App\Actions\Project\ClubAccess;
use App\Actions\Project\ClubAccess\BotRequestEmail;

class BotEmailChange
{
    public function handle($messenger, $telegram, $bot_user, $webhook) {

        $botRequestEmail = new BotRequestEmail();
        $botRequestEmail->handle($bot_user);

        if ($messenger == 'telegram' && isset($webhook['callback_query']['message']['message_id'])) {
            $telegram->answerCallbackQuery(['callback_query_id' => $webhook['callback_query']['id']]);
        }
    }
}
