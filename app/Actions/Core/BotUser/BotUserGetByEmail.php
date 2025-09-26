<?php
namespace App\Actions\Core\BotUser;

use App\Models\Core\BotUser;

class BotUserGetByEmail
{
    public function handle(int $bot_id, string $email) {
        return BotUser::with('bot')
            ->where('bot_id', $bot_id)
            ->where('email', $email)
            ->first();
    }
}
