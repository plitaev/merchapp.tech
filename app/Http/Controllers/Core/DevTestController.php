<?php
namespace App\Http\Controllers\Core;
use App\Actions\Core\DateEnd\DateEnd;
use App\Actions\Core\GetCourseWebhook\GetCourseWebhookCreate;
use App\Actions\Core\Telegram\TelegramChatJoinRequest;
use App\Http\Controllers\Controller;
use App\Models\Core\Bot;
use App\Models\Core\BotUser;
use App\Models\Core\BotUserBanSchedule;
use App\Models\Core\BotUserRecurrentSchedule;
use App\Models\Core\Pay;
use App\Models\Core\Product;
use App\Models\Core\TelegramSendMessageSchedule;
use App\Models\Core\TelegramWebhook;
use App\Models\Core\Sending;

use App\Actions\Core\BotSendMessage\BotSendMessage;

use YooKassa\Client;
use Telegram\Bot\Api;

use App\Models\Core\TelegramChatJoinRequestLog;
use App\Models\Core\GetcourseWebhook;

use Revolution\Google\Sheets\Facades\Sheets;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use Carbon\Carbon;

class DevTestController extends Controller
{
    public function devtest() {
        /*
        return Carbon::parse('2025-09-05')->addDays(30)->format('Y-m-d');
        $diff_days=Carbon::parse('2025-08-12')->startOfDay()->diffInDays('2025-09-05');
        return $diff_days;
        */

        $bot_user = BotUser::find(1115);

        $alldays1 = Pay::with('bot')
            ->select('id', 'product_id')
            ->whereHas('bot', function ($query) use ($bot_user) {
                $query->where('bot_id', $bot_user->bot_id);
            })
            ->where('bot_user_id', $bot_user->id)
            ->where('gift', 0)
            ->where('status', 1)
            ->whereNot('id', 41224)
            ->get();


        $alldays2 = Pay::with('bot')
            ->select('id', 'product_id')
            ->whereHas('bot', function ($query) use ($bot_user) {
                $query->where('bot_id', $bot_user->bot_id);
            })
            ->where('gift_bot_user_id', $bot_user->id)
            ->where('gift', 1)
            ->where('status', 1)
            ->whereNot('id', 41224)
            ->get();

        $alldays = [];
        foreach ($alldays1 as $allday1) $alldays[] = $allday1->id;
        foreach ($alldays2 as $allday2) $alldays[] = $allday2->id;

        $alldays = Pay::select('days', 'created_at', 'updated_at')->whereIn('id', $alldays)->orderBy('created_at')->get();

        $Adates_start=[];
        $Adates_end=[];

        foreach ($alldays as $allday) {

            $Adates_start[]=$allday->created_at->format('Y-m-d');
            $Adates_end[]=$allday->created_at->addDays($allday->days)->format('Y-m-d');
        }

        $days_to_add=0;
        $Adiffs_in_days = [];

        foreach ($Adates_end as $k=>$date) {
            $next_pos=$k+1;
            if (isset($Adates_start[$next_pos])) {
                if ($Adates_start[$next_pos] < $date) {
                    $diff_days=$Adates_start[$next_pos]->startOfDay()->diffInDays($date);
                    $Adiffs_in_days[]=$diff_days;
                    if ($diff_days>0) {
                        $days_to_add=$days_to_add+$diff_days;
                        $Adates_end[$next_pos]=$Adates_end[$next_pos]->addDay($diff_days);
                    }
                }
            }
        }

        //return $Adiffs_in_days;

        foreach ($Adates_end as $date) {
            $date_end=$date;
        }

        return $date_end->format('Y-m-d');

    }
}
