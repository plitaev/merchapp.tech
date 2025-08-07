<?php

namespace App\Actions\Core\TelegramSendInvoice;

use App\Actions\Core\Pay\PayCreateIntoBot;

class TelegramSendInvoice
{
    public function handle($telegram, $bot, $bot_user, $product, $webhook) {
        $payCreateIntoBot = new PayCreateIntoBot();

        $price = $product->price.'00';

        if (isset($webhook['callback_query']['message']['message_id'])) {
            $telegram->answerCallbackQuery(['callback_query_id' => $webhook['callback_query']['id']]);
        }

        $pay = $payCreateIntoBot->handle($bot_user, $bot);

        return $telegram->sendInvoice([
            'provider_token' => $bot->yookassa_provider_token,
            'chat_id' => $bot_user->telegram_chat_id,
            'title' => $product->name,
            'description' => $product->description,
            'payload' => $pay->id,
            'currency' => 'RUB',
            'prices' => [['label' => 'К оплате', 'amount' => $price]]
        ]);
    }
}
