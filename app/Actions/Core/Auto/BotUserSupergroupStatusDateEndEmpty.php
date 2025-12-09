<?php

namespace App\Actions\Core\Auto;

use App\Actions\Core\BotUserSupergroupStatus\BotUserSupergroupStatusGet;

use App\Models\Core\BotUser;

class BotUserSupergroupStatusDateEndEmpty
{
    public function handle() {
        $botUserSupergroupStatus = new BotUserSupergroupStatusGet();

        $bot_user = BotUser::with('bot')->where('run_status', 0)->whereNull('date_end')->first();
        BotUser::where('id', $bot_user->id)->update(['run_status' => 1]);

        $botUserSupergroupStatus->handle($bot_user);
    }
}
