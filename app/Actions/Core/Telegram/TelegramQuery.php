<?php

namespace App\Actions\Core\Telegram;

class TelegramQuery
{
    public function handle($bot, string $api_function, $request_data, bool $return_array = true) {
        $headers = ['Content-Type: application/json'];
        (string) $api_url = env('APP_TELEGRAM_API_URL').'/bot'.$bot->telegram_token.'/'.$api_function;

        $add_to_url = [];

        foreach ($request_data as $key => $value) {
            $item = $key . "=" . $value;
            $add_to_url[] = $item;
        }

        $api_url.= '?' . implode('&', $add_to_url);

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $api_url);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($curl);
        return $api_url;
        curl_close($curl);

        $response = explode('includeSubdomains', $response);
        $response = $response[0];

        return ($return_array?json_decode($response, true):$response);
    }
}
