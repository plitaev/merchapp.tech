<?php
namespace App\Actions\Project\ClubAccess;

use App\Actions\Core\BotUser\BotUserSetListener;

class BotGoToClub
{
    public function handle(string $messenger, $telegram, $webhook, $bot_user) {
        $botRequestAndConfirmEmail = new BotRequestAndConfirmEmail();
        $botUserSetListener = new BotUserSetListener();

        if ($messenger == 'telegram' && isset($webhook['callback_query']['message']['message_id'])) {
            $telegram->answerCallbackQuery(['callback_query_id' => $webhook['callback_query']['id']]);
        }

        $botUserSetListener->handle('sys_go_to_pay', 1, $bot_user->id);
        $botRequestAndConfirmEmail->handle($bot_user);
    }
}
