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

        $order_number = 0;
        $provider_payment_charge_id = '';

        if (isset($requestBody['object']['metadata']['order_number'])) {

            $order_number = $requestBody['object']['metadata']['order_number'];
            $provider_payment_charge_id = $requestBody['object']['id'];

        } else {

            if (isset($requestBody['metadata']['order_number'])) {
                $order_number = $requestBody['metadata']['order_number'];
                $provider_payment_charge_id = $requestBody['id'];
            }

        }

        Pay::query()
            ->where('id', $order_number)
            ->update(
                [
                    'status' => 1,
                    'provider_payment_charge_id' => $provider_payment_charge_id,
                    'pay_system_callback' => $source
                ]
            );

        $pay = Pay::find($order_number);

        $bot_user = BotUser::find($pay->bot_user_id);
        $dateEnd->handle($bot_user, 'Y-m-d');
        $botSendMessage->handle($bot_user, 'SYS_SUCCESS_MESSAGE');
    }
}
