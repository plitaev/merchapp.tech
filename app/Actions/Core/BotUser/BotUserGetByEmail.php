<?php

namespace App\Actions\Core\BotUser;

class BotUserGetByEmail
{
    public function handle(string $email) {
        return \App\Models\Core\BotUser::select('telegram_chat_id', 'first_name', 'last_name', 'hand_name', 'username', 'email', 'listen_email_status')
            ->where('email', $email)
            ->first();
    }
}
