<?php
namespace App\Actions\Core\Bot;

use App\Models\Core\Bot;

class BotGetByID
{
    public function handle(int $bot_id) {
        return Bot::select('telegram_token')->find($bot_id);
    }
}
