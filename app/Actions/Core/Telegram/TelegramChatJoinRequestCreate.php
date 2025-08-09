<?php
namespace App\Actions\Core\Telegram;

use Telegram\Bot\Api;

use App\Models\Core\Bot;
use App\Models\Core\TelegramChatJoinRequestLog;

class TelegramChatJoinRequestCreate
{
    public function handle(int $bot_id, $json, int $status) {
        $bot = Bot::select('telegram_token')->where('id', $bot_id)->first();
        $telegram = new Api($bot->telegram_token);

        if ($status === 1) {
            $result = $telegram->approveChatJoinRequest(['chat_id' => $json['chat_join_request']['chat']['id'], 'user_id' => $json['user_chat_id']]);
        } else {
            $result = $telegram->declineChatJoinRequest(['chat_id' => $json['chat_join_request']['chat']['id'], 'user_id' => $json['user_chat_id']]);
        }

        TelegramChatJoinRequestLog::create([
            'chat_id' => $json['chat_join_request']['chat']['id'],
            'user_id' => $json['user_chat_id'],
            'invite_link' => $json['chat_join_request']['invite_link']['invite_link'],
            'status' => $status,
            'telegram_data' => json_encode($result, true)
        ]);
    }
}
