<?php

namespace App\Actions\Core\Max;

class MaxWebhookMake
{
    public function handle(int $bot_id, string $max_webhook) {
        return env('APP_URL').'/telegram/webhook/'.$bot_id.'/'.$max_webhook;
    }
}
