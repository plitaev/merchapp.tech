<?php
namespace App\Actions\Core\BotSupergroup;

use App\Models\Core\TelegramSupergroup;

class BotSupergroups
{
    public function handle(int $bot_id) {
        return TelegramSupergroup::select('telegram_id')->where('bot_id', $bot_id)->pluck('telegram_id')->toArray();
    }
}
