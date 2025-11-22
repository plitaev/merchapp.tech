<?php
namespace App\Http\Controllers\Core;
use App\Actions\Core\Auto\BotUserSetBanSchedulerCreate;
use App\Actions\Core\DateEnd\DateEnd;
use App\Actions\Core\GetCourseWebhook\GetCourseWebhookCreate;
use App\Actions\Core\Telegram\TelegramChatJoinRequest;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\HMACController;
use App\Models\Core\Bot;
use App\Models\Core\BotUser;
use App\Models\Core\BotUserBanSchedule;
use App\Models\Core\BotUserRecurrentSchedule;
use App\Models\Core\BotUserUnbanSchedule;
use App\Models\Core\Pay;
use App\Models\Core\Product;
use App\Models\Core\TelegramScheduleDeleteMessage;
use App\Models\Core\TelegramSendMessageLog;
use App\Models\Core\TelegramSendMessageSchedule;
use App\Models\Core\TelegramUnbanSchedule;
use App\Models\Core\TelegramWebhook;
use App\Models\Core\Sending;

use App\Actions\Core\BotSendMessage\BotSendMessage;

use App\Models\Core\User;
use YooKassa\Client;
use Telegram\Bot\Api;

use App\Models\Core\TelegramChatJoinRequestLog;
use App\Models\Core\GetcourseWebhook;

use Revolution\Google\Sheets\Facades\Sheets;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use Carbon\Carbon;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Illuminate\Support\Facades\Auth;


class DevTestController extends Controller
{
    public function devtest() {
        $time = "23:30:00";

        $funnel_days = 1;
        $funnel_hours = 3;
        $funnel_minutes = 0;


        $prevous_date = Carbon::now();

        if ($funnel_days > 0) $prevous_date->subDays($funnel_days);

        $date = $prevous_date->format('Y-m-d');

        $datetime = $date." ".$time;
        $datetime = Carbon::parse($datetime);

        if ($funnel_hours) $datetime->addHours($funnel_hours);
        if ($funnel_minutes) $datetime->addMinutes($funnel_minutes);

        $time = $datetime->format('H:i:s');
        $datetime = $datetime->format('Y-m-d H:i:s');

        $A['date'] = $date;
        $A['time'] = $time;
        $A['datetime'] = $datetime;

        return $A;

    }
}
