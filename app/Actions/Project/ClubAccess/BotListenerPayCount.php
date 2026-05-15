<?php

namespace App\Actions\Project\ClubAccess;

use App\Actions\Core\BotSendMessage\BotSendMessage;
use App\Actions\Core\BotUser\BotUserSetListener;
use App\Models\Core\BotUser;

class BotListenerPayCount
{
    public function handle($webhook, $bot_user) {
        $botSendMessage = new BotSendMessage();
        $botUserSetListener = new BotUserSetListener();

        if ($bot_user->listen_pay_count_status == 1) {

            if (isset($webhook['message']['text'])) {

                if (is_numeric($webhook['message']['text'])) {
                    BotUser::where('id', $bot_user->id)->update(['pay_count' => $webhook['message']['text']]);
                    $botSendMessage->handle($bot_user, 'SYS_USER_ENTERED_PAY_COUNT');
                    $botUserSetListener->handle('listen_pay_count', 0, $bot_user->id);

                    $bot_user = BotUser::find($bot_user->id);
                } else {
                    $botSendMessage->handle($bot_user, 'SYS_USER_ENTERED_PAY_COUNT_NON_INT');
                }

            } else {
                $botSendMessage->handle($bot_user, 'SYS_USER_ENTERED_PAY_COUNT_NON_INT');
            }

        }

    }
}
