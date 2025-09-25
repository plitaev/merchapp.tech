<?php
namespace App\Actions\Core\BotSupergroup;

use App\Models\Core\TelegramSupergroup;

class BotSupergroupsAll
{
    public function handle()
    {
        $supergroups = [];
        $res = TelegramSupergroup::all();
        foreach ($res as $data) $supergroups[$data->bot_id][] = $data;

        return $supergroups;
    }
}
