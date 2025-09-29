<?php

namespace App\Actions\Core\Funnel;

use Carbon\Carbon;

class FunnelUserBan
{
    public function handle($data) {

        if ($data->funnel_condition->alias == "user_ban") {

            if ($data->funnel_condition_trigger->alias == "now") {
                $date = date('Y-m-d', time());
                $datetime = $date."".$data->bot->ban_time;
            }

            if ($data->funnel_condition_trigger->alias == "after") {
                $prevous_date = Carbon::now();

                if ($data->funnel_days && $data->funnel_days > 0) $prevous_date->subDays($data->funnel_days);
                if ($data->funnel_hours && $data->funnel_hours > 0) $prevous_date->subHours($data->funnel_hours);
                if ($data->funnel_minutes && $data->funnel_minutes > 0) $prevous_date->subMinutes($data->funnel_hours);

                $date = $prevous_date->format('Y-m-d');
                $datetime = $prevous_date->format('Y-m-d H:i:s');

                return $date." | ".$datetime;
            }

        }

    }
}
