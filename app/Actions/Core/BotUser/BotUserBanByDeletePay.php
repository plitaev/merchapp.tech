<?php

namespace App\Actions\Core\BotUser;

use App\Models\Core\BotUserBanSchedule;

class BotUserBanByDeletePay
{
    public function handle($bot_user) {

        $run_ban = 0;

        if ($bot_user->date_end == date('Y-m-d', time())) {
            $run_ban = 1;
            $ban_datetime = date('Y-m-d', time())." ".$bot_user->bot->ban_time;
        }

        if ($bot_user->date_end < date('Y-m-d', time())) {
            $run_ban = 1;
            $ban_datetime = date('Y-m-d H:i:s', time());
        }

        if (!$bot_user->date_end == NULL) {
            $run_ban = 1;
            $ban_datetime = date('Y-m-d H:i:s', time());
        }

        if ($run_ban == 1) {

            BotUserBanSchedule::create(
                [
                    'bot_user_id' => $bot_user->id,
                    'run_status' => 0,
                    'ban_datetime' => $ban_datetime
                ]
            );

        }

        return $run_ban;
    }
}
