<?php
namespace App\Actions\Core\Auto;

use App\Models\Core\BotUser;
use App\Models\Core\BotUserBanSchedule;

class BotUserSetBanScheduler
{
    public function handle() {
        $date = date('Y-m-d', time());

        $non_runned_users = BotUserBanSchedule::select('bot_user_id')
            ->where('run_status', 0)
            ->where('ban_date', $date)
            ->groupBy('bot_user_id')
            ->pluck('bot_user_id')
            ->toArray();

        $non_runned_users[] = 0;

        $bot_users = BotUser::select('id')
            ->where('date_end', $date)
            ->whereNotIn('id', $non_runned_users)
            ->get();

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
