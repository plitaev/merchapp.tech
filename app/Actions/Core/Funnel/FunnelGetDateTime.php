<?php

namespace App\Actions\Core\Funnel;

class FunnelGetDateTime
{
    public function handle($data) {
        $A = [];

        if ($data->funnel_condition_trigger->alias == "now") {
            $now = time();

            $A['date'] = date('Y-m-d', $now);
            $A['time'] = date('H:i:s', $now);
            $A['datetime'] = date('Y-m-d H:i:s', $now);
        }

        if ($data->funnel_condition_trigger->alias == "after") {
            $prevous_date = Carbon::now();

            if ($data->funnel_days && $data->funnel_days > 0) $prevous_date->subDays($data->funnel_days);
            if ($data->funnel_hours && $data->funnel_hours > 0) $prevous_date->subHours($data->funnel_hours);
            if ($data->funnel_minutes && $data->funnel_minutes > 0) $prevous_date->subMinutes($data->funnel_hours);

            $A['date'] = $prevous_date->format('Y-m-d');
            $A['time'] = $prevous_date->format('H:i:s');
            $A['datetime'] = $prevous_date->format('Y-m-d H:i:s');
        }

        if ($data->funnel_condition_trigger->alias == "before") {
            $next_date = Carbon::now();

            if ($data->funnel_days && $data->funnel_days > 0) $next_date->addDays($data->funnel_days);
            if ($data->funnel_hours && $data->funnel_hours > 0) $next_date->addHours($data->funnel_hours);
            if ($data->funnel_minutes && $data->funnel_minutes > 0) $next_date->addMinutes($data->funnel_hours);

            $A['date'] = $next_date->format('Y-m-d');
            $A['time'] = $next_date->format('H:i:s');
            $A['datetime'] = $next_date->format('Y-m-d H:i:s');
        }

        return $A;
    }
}
