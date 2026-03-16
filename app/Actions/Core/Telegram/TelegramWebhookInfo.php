<?php

namespace App\Actions\Core\Telegram;

class TelegramWebhookInfo
{
    public function handle(?string $token, ?string $webhook) {
        $webhook=str_replace("-","/",$webhook);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://api.telegram.org/bot".$token."/getWebhookInfo?url=".$webhook);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }
}
