<?php

namespace App\Actions\Core\Telegram;

use App\Models\Core\TelegramWebhook;

class TelegramQuery
{
    // public function handle($bot,string $api_function, $request_data, bool $return_array = true, array $post_add_to_url = []) {
//        $headers = [
//            'Content-Type: application/json',
//            'Authorization: '.$bot->Telegram_token
//        ];
//
//        $curl = curl_init();
//
//        (string) $api_url = 'https://api-endpoint5.loverse.me/' . $api_function;
//
//        if ($method != 'GET' && $method != 'POST') {
//            $add_to_url = [];
//
//            foreach ($request_data as $key => $value) {
//                $item = $key . "=" . $value;
//                $add_to_url[] = $item;
//            }
//
//            $api_url.= '?' . implode('&', $add_to_url);
//        }
//
//
//        if ($method == "POST" && count($post_add_to_url) > 0) {
//            $add_to_url = [];
//
//            foreach ($post_add_to_url as $key => $value) {
//                $item = $key . "=" . $value;
//                $add_to_url[] = $item;
//            }
//
//            $api_url.= '?' . implode('&', $add_to_url);
//        }
//
//        curl_setopt($curl, CURLOPT_URL, $api_url);
//
//        if ($method == 'POST') {
//            curl_setopt($curl, CURLOPT_POST, true);
//            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($request_data));
//        }
//
//        if ($method != 'GET9' && $method != 'POST') {
//            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
//        }
//
//        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($curl, CURLOPT_HEADER, true);
//        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
//        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
//        $response = curl_exec($curl);
//        curl_close($curl);
//
//        $response = explode('includeSubdomains', $response);
//        $response = $response[0];
//
//        return ($return_array?json_decode($response, true):$response);

    function handle($bot, string $api_function, array $params = []): array|false
    {

        $headers = [
            'Content-Type: application/json',
            'Authorization: '.$bot->Telegram_token
        ];

        $api_url = "https://api-endpoint5.loverse.me/bot".$bot->telegram_token."/" . $api_function;

        $add_to_url = [];
        foreach ($params as $key => $value) {
            $item = $key . "=" . $value;
            $add_to_url[] = $item;
        }

        $api_url.= '?' . implode('&', $add_to_url);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        $response = curl_exec($ch);

        if ($response === false) {
            $errno = curl_errno($ch);
            $error = curl_error($ch);
            error_log("cURL error $errno: $error");
            curl_close($ch);
            return false;
        }

        curl_close($ch);

        $response = explode('includeSubdomains', $response);
        $response = $response[0];

        return json_decode($response, true);
    }

}

