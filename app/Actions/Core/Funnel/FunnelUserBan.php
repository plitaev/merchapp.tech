<?php
namespace App\Actions\Core\Funnel;

use Carbon\Carbon;

use App\Models\Core\BotUser;
use App\Models\Core\Sending;
use App\Models\Core\TelegramSendMessageSchedule;

class FunnelUserBan
{
    public function handle($data) {

        if ($data->funnel_condition->alias == "user_ban") {

            if ($data->funnel_condition_trigger->alias == "now") {
                $date = date('Y-m-d', time());
                $time = $data->bot->ban_time;
                $datetime = $date." ".$data->bot->ban_time;
            }

            if ($data->funnel_condition_trigger->alias == "after") {
                $prevous_date = Carbon::now();

                if ($data->funnel_days && $data->funnel_days > 0) $prevous_date->subDays($data->funnel_days);

                $date = $prevous_date->format('Y-m-d');
                $time = $prevous_date->format('H:i:s');

                $datetime = $date." ".$data->bot->ban_time;
                $datetime = Carbon::parse($datetime);

                if ($data->funnel_hours && $data->funnel_hours > 0) $datetime->addHours($data->funnel_hours);
                if ($data->funnel_minutes && $data->funnel_minutes > 0) $datetime->addMinutes($data->funnel_hours);

                $time = $datetime->format('H:i:s');
                $datetime = $datetime->format('Y-m-d H:i:s');
            }

            if ($data->funnel_condition_trigger->alias == "before") {
                $next_date = Carbon::now();

                if ($data->funnel_days && $data->funnel_days > 0) $next_date->addDays($data->funnel_days);

                $date = $next_date->format('Y-m-d');
                $time = $next_date->format('H:i:s');

                $datetime = $date." ".$data->bot->ban_time;
                $datetime = Carbon::parse($datetime);

                if ($data->funnel_hours && $data->funnel_hours > 0) $datetime->subHours($data->funnel_hours);
                if ($data->funnel_minutes && $data->funnel_minutes > 0) $datetime->subMinutes($data->funnel_hours);

                $time = $datetime->format('H:i:s');
                $datetime = $datetime->format('Y-m-d H:i:s');
            }

            if (date('H:i:s') >= $time) {

                $schedules = TelegramSendMessageSchedule::whereHas('sending', function ($query) use ($data) {
                    $query->where('bot_message_id', $data->id);
                    $query->where('send_datetime', '>=', date('Y-m-d', time())." 00:00:00");
                    $query->where('send_datetime', '<=', date('Y-m-d', time())." 23:59:59");
                })
                    ->select('bot_user_id')
                    ->groupBy('bot_user_id')
                    ->pluck('bot_user_id')
                    ->toArray();

                $bot_users = BotUser::select('id')->where('bot_id', $data->bot->id)->where('date_end', $date)->whereNotIn('id', $schedules)->get();
                if (count($bot_users) > 0) {

                    $sending = Sending::create([
                        'bot_message_id' => $data->id,
                        'name' => 'Авторассылка по забаненным',
                        'user_ban' => 1,
                        'send_datetime' => date('Y-m-d', time())." ".$time
                    ]);

                    foreach ($bot_users as $bot_user) {
                        TelegramSendMessageSchedule::create([
                            'sending_id' => $sending->id,
                            'bot_user_id' => $bot_user->id
                        ]);
                    }
                }

            }
        }

    }
}
