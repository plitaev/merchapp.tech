<?php

namespace App\Actions\Core\Telegram;

use App\Models\Core\Bot;

class TelegramDirectQuery
{
    public function handle(int $bot_id, string $method, array $params) {
        $bot = Bot::select('telegram_token', 'business_connection_id')->where('id', $bot_id)->first();

        $query = http_build_query($params);
        $url = "https://api.telegram.org/bot".$bot->telegram_token."/".$method."?{$query}";

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }
}
