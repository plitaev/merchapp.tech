<?php

namespace App\Actions\Core\Funnel;

use Carbon\Carbon;

class FunnelUserBan
{
    public function handle($data) {

        if ($data->funnel_condition->alias == "user_ban") {

            if ($data->funnel_condition_trigger->alias == "now") {
                $datetime_start = date('Y-m-d', time())." 00:00:00";
                $datetime_end = date('Y-m-d', time())." 23:59:59";
            }

            if ($data->funnel_condition_trigger->alias == "after") {
                $next_date = Carbon::now();

                if ($data->funnel_days && $data->funnel_days > 0) $next_date->subDays($data->funnel_days);
                if ($data->funnel_hours && $data->funnel_hours > 0) $next_date->subHours($data->funnel_hours);
                if ($data->funnel_minutes && $data->funnel_minutes > 0) $next_date->subMinutes($data->funnel_hours);

                $datetime_start = $next_date." 00:00:00";
                $datetime_end = $next_date." 23:59:59";
            }

        }

    }
}
