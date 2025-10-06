<?php

namespace App\Actions\Core\Auto;

use App\Models\Core\BotUserBanSchedule;

class BotUserSetBanSchedulerCreate
{
    public function handle($bot_users) {

        foreach ($bot_users as $bot_user) {

            $ban_datetime = date('Y-m-d', time()).' '.$bot_user->bot->ban_time;

            $check = BotUserBanSchedule::where('ban_datetime', $ban_datetime)->where('bot_user_id', $bot_user->id)->count();

            if ($check == 0) {
                BotUserBanSchedule::create(
                    [
                        'bot_user_id' => $bot_user->id,
                        'run_status' => 0,
                        'ban_datetime' => $ban_datetime
                    ]
                );
            }

        }

    }
}
