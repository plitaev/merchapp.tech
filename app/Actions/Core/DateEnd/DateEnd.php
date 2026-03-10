<?php
namespace App\Actions\Core\DateEnd;

use Carbon\Carbon;

use App\Models\Core\BotUser;
use App\Models\Core\Pay;

class DateEnd
{
    public function handle($bot_user, string $format) {

        $alldays1 = Pay::with('bot')
            ->select('id', 'product_id')
            ->whereHas('bot', function ($query) use ($bot_user) {
                $query->where('bot_id', $bot_user->bot_id);
            })
            ->where('bot_user_id', $bot_user->id)
            ->where('gift', 0)
            ->where('status', 1)
            ->get();


        $alldays2 = Pay::with('bot')
            ->select('id', 'product_id')
            ->whereHas('bot', function ($query) use ($bot_user) {
                $query->where('bot_id', $bot_user->bot_id);
            })
            ->where('gift_bot_user_id', $bot_user->id)
            ->where('gift', 1)
            ->where('status', 1)
            ->get();

        $alldays = [];
        foreach ($alldays1 as $allday1) $alldays[] = $allday1->id;
        foreach ($alldays2 as $allday2) $alldays[] = $allday2->id;

        $alldays = Pay::select('days', 'created_at', 'payed_at', 'updated_at')->whereIn('id', $alldays)->orderBy('payed_at')->get();

        $Adates_start=[];
        $Adates_end=[];

        foreach ($alldays as $allday) {

            $Adates_start[]=Carbon::parse($allday->payed_at);
            $Adates_end[]=Carbon::parse($allday->payed_at)->addDays($allday->days);
        }

        $days_to_add=0;

        foreach ($Adates_end as $k=>$date) {
            $next_pos=$k+1;
            if (isset($Adates_start[$next_pos])) {
                if ($Adates_start[$next_pos] < $date) {
                    $diff_days=$Adates_start[$next_pos]->startOfDay()->diffInDays($date);
                    $diff_days = floor($diff_days);
                    if ($diff_days>0) {
                        $days_to_add=$days_to_add+$diff_days;
                        $Adates_end[$next_pos]=$Adates_end[$next_pos]->addDay($diff_days);
                    }
                }
            }
        }

        foreach ($Adates_end as $date) {
            $date_end=$date;
        }

        if (isset($date_end)) {
            BotUser::where('id', $bot_user->id)->update(['date_end' => date('Y-m-d', strtotime($date_end))]);
            return date($format, strtotime($date_end));
        } else {
            BotUser::where('id', $bot_user->id)->update(['date_end' => NULL]);
            return '';
        }

    }
}
