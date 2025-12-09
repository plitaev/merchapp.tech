<?php

namespace App\Actions\Core\Auto;

use App\Actions\Core\BotUserSupergroupStatus\BotUserSupergroupStatusGet;

use App\Models\Core\BotUser;

class BotUserSupergroupStatusDateEndExpired
{
    public function handle() {
        $botUserSupergroupStatusGet = new BotUserSupergroupStatusGet();

        $bot_user = BotUser::with('bot')->where('run_status', 0)->where('date_end', '<', '2025-12-01')->first();
        BotUser::where('id', $bot_user->id)->update(['run_status' => 1]);

        $botUserSupergroupStatusGet->handle($bot_user);
    }
}
