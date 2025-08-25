<?php

namespace App\Actions\Core\Auto;

use App\Models\Core\BotUserBanSchedule;

class BotUserSetBanSchedulerCreate
{
    public function handle($bot_users, $date) {

        foreach ($bot_users as $bot_user) {
            BotUserBanSchedule::create(
                [
                    'bot_user_id' => $bot_user->id,
                    'run_status' => 0,
                    'ban_date' => $date,
                    'ban_time' => '09:00:00'
                ]
            );
        }

    }
}
