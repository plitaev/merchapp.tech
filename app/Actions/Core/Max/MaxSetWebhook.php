<?php
<?php

namespace App\Actions\Core\Max;

use Illuminate\Support\Facades\Log;

class MaxSetWebhook
{
    public function handle(int $id, string $token, string $webhook): string
    {
        // Безопасная сборка URL
        $appUrl = rtrim(env('APP_URL'), '/');
        $webhookUrl = urlencode("{$appUrl}/telegram/webhook/{$id}/{$webhook}");

        $apiUrl = "https://api.max.org/bot{$token}/setWebhook?url={$webhookUrl}";

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_FAILONERROR => true,
        ]);

        $result = curl_exec($curl);
        $error = curl_error($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        // Логирование для отладки
        if ($error) {
            Log::error('MaxSetWebhook cURL error', [
                'error' => $error,
                'http_code' => $httpCode,
                'url' => $apiUrl,
            ]);
            throw new \RuntimeException("Failed to set webhook: {$error}");
        }

        // Проверка HTTP кода
        if ($httpCode !== 200) {
            Log::warning('MaxSetWebhook non-200 response', [
                'http_code' => $httpCode,
                'response' => $result,
                'url' => $apiUrl,
            ]);
        }

        return $result ?: '';
    }
}