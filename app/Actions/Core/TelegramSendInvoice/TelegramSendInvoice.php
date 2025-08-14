<?php

namespace App\Actions\Core\TelegramSendInvoice;

use App\Actions\Core\Pay\PayCreateIntoBot;

use App\Models\Core\PaySystem;
use App\Models\Core\TelegramSendInvoiceLog;
use App\Models\Core\TelegramSendInvoiceErrorLog;

class TelegramSendInvoice
{
    public function handle($telegram, $bot, $bot_user, $product, $webhook) {
        $payCreateIntoBot = new PayCreateIntoBot();

        $price = $product->price.'00';

        if (isset($webhook['callback_query']['message']['message_id'])) {
            $telegram->answerCallbackQuery(['callback_query_id' => $webhook['callback_query']['id']]);
        }

        $pay = $payCreateIntoBot->handle($bot_user, $bot);

        $provider_token = NULL;
        $pay_system_id = NULL;

        if (isset($bot->yookassa_provider_token)) {
            $provider_token = $bot->yookassa_provider_token;
            $pay_system = PaySystem::where('alias', 'yookassa')->first();
        }

        if (isset($pay_system)) $pay_system_id = $pay_system->id;

        try {

            $invoice = $telegram->sendInvoice([
                'provider_token' => $provider_token,
                'pay_system_id' => $pay_system_id,
                'chat_id' => $bot_user->telegram_chat_id,
                'title' => $product->name,
                'description' => $product->description,
                'payload' => $pay->id,
                'currency' => 'RUB',
                'prices' => [['label' => 'К оплате', 'amount' => $price]]
            ]);

            TelegramSendInvoiceLog::create(
                [
                    'bot_user_id' => $bot_user->id,
                    'chat_id' => $bot_user->telegram_chat_id,
                    'invoice' => json_encode($invoice, true)
                ]
            );

        } catch (\Exception $exception) {
            TelegramSendInvoiceErrorLog::create(['chat_id' => $bot_user->telegram_chat_id, 'text' => $exception]);
        }
    }
}
