<?php

namespace App\Actions\Core\Telegram;

class TelegramWebhookMake
{
    public function handle(int $bot_id, ?string $telegram_webhook) {
        return env('APP_URL').'/telegram/webhook/'.$bot_id.'/'.$telegram_webhook;
    }
}
