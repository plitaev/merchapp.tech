<?php

namespace App\Actions\Core\Max;

class MaxWebhookInfo
{
    public function handle(string $token, string $webhook) {
        $webhook = str_replace('-', '/', $webhook);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://platform-api.max.ru/me/$token/getWebhookInfo?url=$webhook");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);
        if (curl_errno($curl)) {
            error_log(curl_error($curl));
            return null;
        }

        curl_close($curl);
        return $result;
    }
}
