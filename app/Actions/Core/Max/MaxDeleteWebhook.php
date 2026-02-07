<?php

namespace App\Actions\Core\Max;

use Illuminate\Support\Facades\Log;

class MaxDeleteWebhook
{
    /**
     * Удаляем webhook у платформы Max.
     *
     * @param string $token   Токен бота в Max
     * @param string $webhook Любой внутренний идентификатор, если нужен
     * @return bool|string    Raw-ответ платформы или false при ошибке
     */
    public function handle(string $token, string $webhook = '')
    {
        // Формируем «обратный» URL, на который больше не хотим получать updates.
        // Если у вас именованный маршрут «telegram.webhook», можно использовать:
        //   $appUrl = route('telegram.webhook', ['token' => $token], true);
        // Ниже — универсальный вариант:
        $appUrl = url("/telegram/webhook/{$token}");

        $query = http_build_query(['url' => $appUrl]);

        $ch = curl_init("https://platform-api.max.ru/{$token}/deleteWebhook?{$query}");
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        $response = curl_exec($ch);
        $error    = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);

        if ($error || $httpCode >= 400) {
            Log::error('Max deleteWebhook failed', [
                'token'    => $token,
                'url'      => $appUrl,
                'httpCode' => $httpCode,
                'error'    => $error,
                'response' => $response,
            ]);
            return false;
        }

        return $response;
    }
}