<?php
namespace App\Actions\Core\Funnel;

use Carbon\Carbon;

use App\Models\Core\BotUser;
use App\Models\Core\TelegramSendMessageSchedule;

class FunnelUserBan
{
    public function handle($data) {

        if ($data->funnel_condition->alias == "user_ban") {

            if ($data->funnel_condition_trigger->alias == "now") {
                $date = date('Y-m-d', time());
                $time = $data->bot->ban_time;
                $datetime = $date.$data->bot->ban_time;
            }

            if ($data->funnel_condition_trigger->alias == "after") {
                $prevous_date = Carbon::now();

                if ($data->funnel_days && $data->funnel_days > 0) $prevous_date->subDays($data->funnel_days);
                if ($data->funnel_hours && $data->funnel_hours > 0) $prevous_date->subHours($data->funnel_hours);
                if ($data->funnel_minutes && $data->funnel_minutes > 0) $prevous_date->subMinutes($data->funnel_hours);

                $date = $prevous_date->format('Y-m-d');
                $time = $prevous_date->format('H:i:s');
                $datetime = $prevous_date->format('Y-m-d H:i:s');
            }

            if ($data->funnel_condition_trigger->alias == "before") {
                $next_date = Carbon::now();

                if ($data->funnel_days && $data->funnel_days > 0) $next_date->addDays($data->funnel_days);
                if ($data->funnel_hours && $data->funnel_hours > 0) $next_date->addHours($data->funnel_hours);
                if ($data->funnel_minutes && $data->funnel_minutes > 0) $next_date->addMinutes($data->funnel_hours);

                $date = $next_date->format('Y-m-d');
                $time = $next_date->format('H:i:s');
                $datetime = $next_date->format('Y-m-d H:i:s');
            }

            $schedules = TelegramSendMessageSchedule::whereHas('sending', function ($query) use ($data) {
                $query->where('id', $data->id);
                $query->where('send_datetime', '>=', date('Y-m-d', time())." 00:00:00");
                $query->where('send_datetime', '<=', date('Y-m-d', time())." 23:59:59");
            })
                ->select('bot_user_id')
                ->groupBy('bot_user_id')
                ->pluck('bot_user_id')
                ->toArray();

            return $schedules;

            $bot_users = BotUser::select('id')->where('bot_id', $data->bot->id)->where('date_end', $date)->get();
            return $bot_users;
        }

    }
}
