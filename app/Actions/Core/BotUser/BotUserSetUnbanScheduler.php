<?php

namespace App\Actions\Core\BotUser;

use App\Models\Core\BotUserUnbanSchedule;

class BotUserSetUnbanScheduler
{
    public function handle($bot_user, $datetime) {

        BotUserUnbanSchedule::create([
            'bot_user_id' => $bot_user->id,
            'run_status' => 0,
            'unban_datetime' => $datetime
        ]);

    }
}
