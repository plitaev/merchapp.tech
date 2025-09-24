<?php
namespace App\Actions\Core\Auto;

use App\Actions\Core\Auto\BotUserSetBanSchedulerCreate;

use App\Models\Core\BotUser;
use App\Models\Core\BotUserBanSchedule;

class BotUserSetBanScheduler
{
    public function handle() {

        //== init

        $botUserSetBanSchedulerCreate = new BotUserSetBanSchedulerCreate();
        $date = date('Y-m-d', time());

        $datetime_ban = $date." 23:30:00";
        $datetime_start = $date." 00:00:00";
        $datetime_end = $date." 23:59:59";

        //== Первая выборка - у кого date_end на сегодня

        $non_runned_users = BotUserBanSchedule::select('bot_user_id')
            ->where('run_status', 0)
            ->where('ban_datetime', '>=', $datetime_start)
            ->where('ban_datetime', '<=', $datetime_end)
            ->groupBy('bot_user_id')
            ->pluck('bot_user_id')
            ->toArray();

        $non_runned_users[] = 0;

        $bot_users = BotUser::select('id')
            ->where('date_end', $date)
            ->whereNotIn('id', $non_runned_users)
            ->get();

        return $bot_users;

        $botUserSetBanSchedulerCreate->handle($bot_users, $datetime_ban);

        //== Вторая выборка - у кого date_end IS NULL и listen_success_message_status = 1

        $bot_users = BotUser::select('id')
            ->whereNull('date_end')
            ->where('listen_success_message_status', 1)
            ->whereNotIn('id', $non_runned_users)
            ->get();

        $botUserSetBanSchedulerCreate->handle($bot_users, $datetime_ban);
    }
}
