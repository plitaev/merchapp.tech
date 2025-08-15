<?php

namespace App\Actions\Core\Yookassa;

use App\Actions\Core\BotSendMessage\BotSendMessage;
use App\Actions\Core\BotUser\BotUserGetFromTelegram;
use App\Actions\Core\DateEnd\DateEnd;

use App\Models\Core\BotUser;
use App\Models\Core\Pay;

class YookassaMakePaySuccessful
{
    public function handle(array $requestBody, string $source) {
        $botSendMessage = new BotSendMessage();
        $botUserGetFromTelegram = new BotUserGetFromTelegram();
        $dateEnd = new DateEnd();

        Pay::query()
            ->where('id', $requestBody['object']['metadata']['order_number'])
            ->update(
                [
                    'status' => 1,
                    'provider_payment_charge_id' => $requestBody['object']['id'],
                    'pay_system_callback' => $source
                ]
            );

        $pay = Pay::find($requestBody['object']['metadata']['order_number']);

        $bot_user = BotUser::find($pay->bot_user_id);
        $dateEnd->handle($bot_user, 'Y-m-d');
        $botSendMessage->handle($bot_user, 'SYS_SUCCESS_MESSAGE');
    }
}
