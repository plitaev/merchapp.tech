<?php

namespace App\Actions\Project\ClubAccess;

use App\Actions\Core\BotSendMessage\BotSendMessage;
use App\Actions\Core\BotUser\BotUserSetListener;
use App\Models\Core\BotUser;

class BotListenerPhone
{
    public function handle($webhook, $bot_user) {
        $botSendMessage = new BotSendMessage();
        $botUserSetListener = new BotUserSetListener();

        if ($bot_user->listen_phone == 1) {

            if (isset($webhook['message']['text'])) {
                BotUser::where('id', $bot_user->id)->update(['phone' => $webhook['message']['text']]);
                $botUserSetListener->handle('listen_phone', 0, $bot_user->id);

                $botSendMessage->handle($bot_user, 'USER_PHONE_ENTER_SUCCESS');
            }

        }

    }
}
