<?php

namespace App\Actions\Project\ClubAccess;

use App\Actions\Core\BotSendMessage\BotSendMessage;
use App\Actions\Core\BotUser\BotUserSetListener;
use App\Models\Core\BotUser;

class BotListenerPayCount
{
    public function handle(string $messenger, $webhook, $bot_user) {
        $botSendMessage = new BotSendMessage();
        $botUserSetListener = new BotUserSetListener();

        if ($bot_user->listen_pay_count_status == 1) {

            if (($messenger == 'telegram' && isset($webhook['message']['text'])) || ($messenger == 'max' && (isset($webhook['message']['body']['text'])))) {

                if ($messenger == 'telegram') (string) $pay_count = $webhook['message']['text'];
                if ($messenger == 'max') (string) $pay_count = $webhook['message']['body']['text'];

                if (is_numeric($pay_count)) {
                    BotUser::where('id', $bot_user->id)->update(['pay_count' => $pay_count]);
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
