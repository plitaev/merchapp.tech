<?php

namespace App\Actions\Core\Max;

use App\Models\Core\MaxWebhook;

class MaxQuery
{
    public function handle($bot, string $method, string $api_function, $request_data, bool $return_array = true) {
        $headers = [
            'Content-Type: application/json',
            'Authorization: '.$bot->max_token
        ];

        $curl = curl_init();

        (string) $api_url = 'https://platform-api.max.ru/' . $api_function;

        if ($method != 'GET' && $method != 'POST') {
            $add_to_url = [];

            foreach ($request_data as $key => $value) {
                $item = $key . "=" . $value;
                $add_to_url[] = $item;
            }

            $api_url .= $api_url . '?' . implode('&', $add_to_url);


            return $api_url;
        }

        curl_setopt($curl, CURLOPT_URL, $api_url);

        if ($method == 'POST') {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($request_data));
        }

        if ($method != 'GET' && $method != 'POST') {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($curl);
        curl_close($curl);

        return ($return_array?json_decode($response, true):$response);
    }
}
