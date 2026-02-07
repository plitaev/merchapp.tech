<?php

namespace App\Actions\Core\Max;

class MaxSetWebhook
{
    public function handle(int $id, string $token, string $webhook) {

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://api.max.org/bot".$token."/setWebhook?url=".env('APP_URL').'/telegram/webhook/'.$id.'/'.$webhook);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;

    }
}
