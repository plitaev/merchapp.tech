<?php

namespace App\Actions\Core\Telegram;

use App\Models\Core\TelegramChatJoinRequestLog;

class TelegramChatJoinRequestLogCreate
{
    public function handle($json, int $status) {
        TelegramChatJoinRequestLog::create([
            'chat_id' => $json['chat_join_request']['chat']['id'],
            'user_id' => $json['user_chat_id'],
            'invite_link' => $json['chat_join_request']['invite_link']['invite_link'],
            'status' => $status,
            'telegram_data' => json_encode($json, true)
        ]);
    }
}
