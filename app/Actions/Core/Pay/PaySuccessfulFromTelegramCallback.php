<?php
namespace App\Actions\Core\Pay;

use App\Actions\Core\BotSendMessage\BotSendMessage;
use App\Actions\Core\BotUser\BotUserGetFromTelegram;

use App\Models\Core\Pay;

class PaySuccessfulFromTelegramCallback
{
    public function handle(int $bot_id, $json) {
        $botSendMessage = new BotSendMessage();
        $botUserGetFromTelegram = new BotUserGetFromTelegram();

        Pay::query()
            ->where('id', $json['message']['successful_payment']['invoice_payload'])
            ->update(
                [
                    'status' => 1,
                    'provider_payment_charge_id' => $json['message']['successful_payment']['provider_payment_charge_id'],
                    'telegram_payment_charge_id' => $json['message']['successful_payment']['telegram_payment_charge_id'],
                ]
            );

        $bot_user = $botUserGetFromTelegram->handle($bot_id, $json['message']['chat']['id']);
        $botSendMessage->handle($bot_user, 'SYS_MAIN_MENU_MESSAGE');
    }
}
