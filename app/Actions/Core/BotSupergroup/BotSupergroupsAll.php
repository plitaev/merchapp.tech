<?php
namespace App\Actions\Core\BotSupergroup;

use App\Models\Core\TelegramSupergroup;

class BotSupergroupsAll
{
    public function handle()
    {
        $supergroups = [];
        $res = TelegramSupergroup::select('bot_id', 'telegram_id')->get();
        foreach ($res as $data) $supergroups[$data->bot_id][] = $data->telegram_id;

        return $supergroups;
    }
}
