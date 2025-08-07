<?php

namespace App\Actions\Core\TelegramSendInvoice;

class TelegramSendInvoice
{
    public function handle($telegram, $bot, $bot_user, $product) {
        $price = $product->price.'00';

        return $telegram->sendInvoice([
            'provider_token' => $bot->yookassa_provider_token,
            'chat_id' => $bot_user->telegram_chat_id,
            'title' => $product->name,
            'description' => $product->description,
            'payload' => 'payload_text',
            'currency' => 'RUB',
            'prices' => [['label' => 'К оплате', 'amount' => $price]]
        ]);
    }
}
