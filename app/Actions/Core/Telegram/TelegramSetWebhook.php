<?php

namespace App\Actions\Core\Telegram;

class TelegramSetWebhook
{
    public function handle(int $id, string $token, string $webhook) {

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://api-endpoint5.loverse.me/bot".$token."/setWebhook?url=".(isset(env('APP_PROXY_URL'))?env('APP_PROXY_URL'):env('APP_URL')).'/telegram/webhook/'.$id.'/'.$webhook);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;

    }
}
