<?php

namespace App\Actions\Core\Max;

class MaxWebhookInfo
{
    public function handle(?string $token, ?string $webhook) {

        $webhook=str_replace("-","/",$webhook);

        $webhookUrl = "https://platform-api.max.ru/bot".$token."/url=".$webhook;

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $webhookUrl);
        curl_setopt($curl, CURLOPT_POST, true);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Authorization: {$token}",
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($response == false) {
            $response = 'Ошибка cURL: ' . curl_error($curl);
        } else {
            $response .= 'Ответ сервера: ' . $response.'<br>';
            $response .= 'HTTP-статус: ' . $httpCode;
        }

        curl_close($curl);
        return $response;

    }
}
