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

        //== Первая выборка - у кого date_end на сегодня

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
        $botUserSetBanSchedulerCreate->handle($bot_users, $date);

        //== Вторая выборка - у кого date_end IS NULL и listen_success_message_status = 1

        $bot_users = BotUser::select('id')
            ->whereNull('date_end')
            ->where('listen_success_message_status', 1)
            ->whereNotIn('id', $non_runned_users)
            ->get();

        $botUserSetBanSchedulerCreate->handle($bot_users, $date);
    }
}
