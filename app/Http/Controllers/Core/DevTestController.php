<?php
namespace App\Http\Controllers\Core;

use Carbon\Carbon;

use App\Http\Controllers\Controller;

use App\Models\Core\BotUser;
use App\Models\Core\BotUserBanSchedule;
use App\Models\Core\BotUserPrice;
use App\Models\Core\GetcourseWebhookTicket;
use App\Models\Core\Product;
use Illuminate\Support\Facades\DB;
use Telegram\Bot\Api;

use App\Models\Core\Pay;

use App\Actions\Core\DateEnd\DateEndNew;

class DevTestController extends Controller
{
    public function devtest() {

        $bot_user = BotUser::find(1);
        $format = 'Y-m-d';

        $A = [24746, 25891, 36884, 47190, 53550, 71154];
        $A = [24746, 25891, 47190, 53550, 71154];

        $alldays1 = Pay::with('bot')
            ->select('id', 'product_id')
            ->whereHas('bot', function ($query) use ($bot_user) {
                $query->where('bot_id', $bot_user->bot_id);
            })
            ->where('bot_user_id', $bot_user->id)
            ->where('gift', 0)
            ->where('status', 1)
            ->whereIn('id', $A)
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

        $alldays = Pay::select('days', 'created_at', 'updated_at')->whereIn('id', $alldays)->orderBy('created_at')->get();

        $Adates_start=[];
        $Adates_end=[];

        foreach ($alldays as $allday) {

            $Adates_start[]=$allday->created_at;
            $Adates_end[]=$allday->created_at->addDays($allday->days);
        }

        $days_to_add=0;

        foreach ($Adates_end as $k=>$date) {
            $next_pos=$k+1;
            if (isset($Adates_start[$next_pos])) {
                if ($Adates_start[$next_pos] < $date) {
                    $diff_days=$Adates_start[$next_pos]->startOfDay()->diffInDays($date);
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

        return $date->format('d.m.Y');

        if (isset($date_end)) {
            BotUser::where('id', $bot_user->id)->update(['date_end_new' => date('Y-m-d', strtotime($date_end))]);
            return date($format, strtotime($date_end));
        } else {
            BotUser::where('id', $bot_user->id)->update(['date_end_new' => NULL]);
            return '';
        }



    }

    public function paycounts() {

    }

}
