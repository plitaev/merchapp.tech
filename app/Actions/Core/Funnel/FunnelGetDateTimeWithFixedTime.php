<?php
namespace App\Actions\Core\Funnel;
use Carbon\Carbon;

class FunnelGetDateTimeWithFixedTime
{
    public function handle($data, $time) {
        $A = [];

        if ($data->funnel_condition_trigger->alias == "now") {

            $date = date('Y-m-d', time());
            $datetime = $date." ".$time;

            $A['date'] = $date;
            $A['time'] = $time;
            $A['datetime'] = $datetime;

        }

        if ($data->funnel_condition_trigger->alias == "after") {
            $prevous_date = Carbon::now();

            if ($data->funnel_days && $data->funnel_days > 0) $prevous_date->subDays($data->funnel_days);

            $date = $prevous_date->format('Y-m-d');

            $datetime = $date." ".$time;
            $datetime = Carbon::parse($datetime);

            if ($data->funnel_hours && $data->funnel_hours > 0) $datetime->addHours($data->funnel_hours);
            if ($data->funnel_minutes && $data->funnel_minutes > 0) $datetime->addMinutes($data->funnel_minutes);

            $time = $datetime->format('H:i:s');
            $datetime = $datetime->format('Y-m-d H:i:s');

            $A['date'] = $date;
            $A['time'] = $time;
            $A['datetime'] = $datetime;

        }

        if ($data->funnel_condition_trigger->alias == "before") {
            $next_date = Carbon::now();

            if ($data->funnel_days && $data->funnel_days > 0) $next_date->addDays($data->funnel_days);

            $date = $next_date->format('Y-m-d');

            $datetime = $date." ".$time;
            $datetime = Carbon::parse($datetime);

            if ($data->funnel_hours && $data->funnel_hours > 0) $datetime->subHours($data->funnel_hours);
            if ($data->funnel_minutes && $data->funnel_minutes > 0) $datetime->subMinutes($data->funnel_minutes);

            $time = $datetime->format('H:i:s');
            $datetime = $datetime->format('Y-m-d H:i:s');

            $A['date'] = $date;
            $A['time'] = $time;
            $A['datetime'] = $datetime;

        }

        return $A;
    }
}
