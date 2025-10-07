<?php

namespace App\Actions\Core\BotUser;

use App\Models\Core\BotUser;

class BotUserGetFromTelegram
{
    public function handle(int $bot_id, int $chat_id) {
        return BotUser::with('bot')->where('bot_id', $bot_id)->where('telegram_chat_id', $chat_id)->first();
    }
}
