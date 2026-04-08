<?php

namespace App\Actions\Project\ClubAccess;

use App\Actions\Core\BotSendMessage\BotSendMessage;
use App\Actions\Core\BotUser\BotUserSetListener;
use App\Models\Core\BotUser;

class BotListenerPhone
{
    public function handle(string $messenger, $webhook, $bot_user) {
        $botSendMessage = new BotSendMessage();
        $botUserSetListener = new BotUserSetListener();

        if ($bot_user->listen_phone_status == 1) {

            if (($messenger == 'telegram' && isset($webhook['message']['text'])) || ($messenger == 'max' && (isset($webhook['message']['body']['text'])))) {

                if ($messenger == 'telegram') (string) $phone = $webhook['message']['text'];
                if ($messenger == 'max') (string) $phone = $webhook['message']['body']['text'];

                BotUser::where('id', $bot_user->id)->update(['phone' => $phone]);
                $botUserSetListener->handle('listen_phone', 0, $bot_user->id);

                $botSendMessage->handle($bot_user, 'USER_PHONE_ENTER_SUCCESS');
            }

        }

    }
}
