<?php
namespace App\Actions\Core\Auto;

use Carbon\Carbon;

use App\Actions\Core\Auto\BotUserSetBanSchedulerCreate;

use App\Models\Core\BotUser;
use App\Models\Core\BotUserBanSchedule;
use App\Models\Core\TelegramSupergroup;


class BotUserSetBanScheduler
{
    public function handle() {

        //== init

        $botUserSetBanSchedulerCreate = new BotUserSetBanSchedulerCreate();
        $date = date('Y-m-d', time());

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

        $bot_users = BotUser::with('bot:id,ban_time')
            ->select('id', 'bot_id')
            ->where('date_end', $date)
            ->whereNotIn('id', $non_runned_users)
            ->get();

        $botUserSetBanSchedulerCreate->handle($bot_users, date('Y-m-d', time()));

        //== Вторая выборка - у кого date_end IS NULL и listen_success_message_status = 1

        $bot_users = BotUser::with('bot:id,ban_time')
            ->select('id', 'bot_id')
            ->whereNull('date_end')
            ->where('listen_success_message_status', 1)
            ->whereNotIn('id', $non_runned_users)
            ->where('ban', 0)
            ->get();

        $botUserSetBanSchedulerCreate->handle($bot_users, date('Y-m-d', time()));

        //== Третья выборка - удаление из чата ДО наступления date_end

        $res = TelegramSupergroup::select('supergroup_delete_days')->where('supergroup_delete_parameter_id', 2)->groupBy('supergroup_delete_days')->pluck('supergroup_delete_days')->toArray();
        $dates = [];
        foreach ($res as $day) {
            $new_date = Carbon::parse($date)->addDays($day)->format('Y-m-d');
            $dates[] = $new_date;
        }

        $bot_users = BotUser::with('bot:id,ban_time')
            ->select('id', 'bot_id')
            ->whereIn('date_end', $dates)
            ->whereNotIn('id', $non_runned_users)
            ->get();

        $botUserSetBanSchedulerCreate->handle($bot_users, date('Y-m-d', time()));


    }
}
