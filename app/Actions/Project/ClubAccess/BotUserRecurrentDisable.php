<?php

namespace App\Actions\Project\ClubAccess;

use App\Models\Core\BotUser;

use App\Actions\Core\BotSendMessage\BotSendMessage;

class BotUserRecurrentDisable
{
    public function handle($telegram, $bot_user, $webhook) {

        $botSendMessage = new BotSendMessage();

        if (isset($webhook['callback_query']['message']['message_id'])) {
            $telegram->answerCallbackQuery(['callback_query_id' => $webhook['callback_query']['id']]);
        }

        BotUser::where('id', $bot_user->id)->update(['recurrent' => 0]);
        $botSendMessage->handle($bot_user, 'SYS_CABINET_AFTER_RECURRENT_DISABLED');
    }
}
