<?php
namespace App\Actions\Core\BotUser;

use App\Models\Core\BotUser;

class BotUserGetByEmail
{
    public function handle(int $bot_id, string $email) {
        return BotUser::select('telegram_chat_id', 'first_name', 'last_name', 'hand_name', 'username', 'email', 'listen_email_status')
            ->where('bot_id', $bot_id)
            ->where('email', $email)
            ->first();
    }
}
