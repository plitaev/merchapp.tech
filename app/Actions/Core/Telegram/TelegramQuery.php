<?php

namespace App\Actions\Core\Telegram;

class TelegramQuery
{
    public function handle($bot, string $api_function, $request_data, bool $return_array = true) {
        (string) $api_url = env('APP_TELEGRAM_API_URL').'/bot'.$bot->telegram_token.'/'.$api_function;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $api_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($request_data));
        $response = curl_exec($curl);
        curl_close($curl);

        $response = explode('includeSubdomains', $response);
        $response = $response[0];

        return ($return_array?json_decode($response, true):$response);
    }
}
