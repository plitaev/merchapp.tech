<?php

namespace App\Actions\Project\ClubAccess;

use App\Models\Core\BotUser;

use App\Actions\Core\BotSendMessage\BotSendMessage;
use App\Models\Core\BotUserRecurrentSchedule;

class BotUserRecurrentDisable
{
    public function handle($telegram, $bot_user, $webhook) {

        $botSendMessage = new BotSendMessage();

        if (isset($webhook['callback_query']['message']['message_id'])) {
            $telegram->answerCallbackQuery(['callback_query_id' => $webhook['callback_query']['id']]);
        }

        BotUser::where('id', $bot_user->id)->update(['recurrent' => 0]);
        BotUserRecurrentSchedule::where('bot_user_id', $bot_user->id)->where('run_status', 0)->update(['run_status' => 3]);

        $botSendMessage->handle($bot_user, 'SYS_CABINET_AFTER_RECURRENT_DISABLED');
    }
}
