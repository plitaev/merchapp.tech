<?php

namespace App\Actions\Core\Telegram;

class TelegramDeleteWebhook
{
    public function handle(string $token, string $webhook) {

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://api-endpoint5.loverse.me/bot".$token."/deleteWebhook?url=".env('APP_URL').'/telegram/webhook/'.$webhook);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;

    }
}
