<?php

namespace App\Actions\Core\Telegram;

class TelegramSetWebhook
{
    public function handle(int $id, string $token) {

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://api.telegram.org/bot".$token."/setWebhook?url=".env('APP_URL').'/telegram/webhook/'.$id);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;

    }
}
