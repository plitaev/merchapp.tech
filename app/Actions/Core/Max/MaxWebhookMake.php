<?php

namespace App\Actions\Core\Max;

class MaxWebhookMake
{
    public function handle(int $bot_id, ?string $webhook) {
        return env('APP_URL').'/max/webhook/'.$bot_id.'/'.$webhook;
    }
}
