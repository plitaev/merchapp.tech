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
use App\Models\Core\BotUserPrice;
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

        //$bot_users = Pay::select('bot_user_id')->where('product_id', 28)->where('status', 1)->groupBy('bot_user_id')->pluck('bot_user_id')->toArray();
        $bot_users = [20864,18503,18046,16850,16804,16241,16026,15650,15583,13545,13471,13116,12722,12440,11800,11716,11449,10748,10326,10110,9506,9018,7980,7661,7229,7075,5878,4797,4604,4521,2783,2040,1338,118,117,91];
        BotUserPrice::whereIn('bot_user_id', $bot_users)->where('product_id', 1)->update(['price' => 1490]);
        BotUserPrice::whereIn('bot_user_id', $bot_users)->where('product_id', 3)->update(['price' => 12900]);

        return $bot_users;

    }
}
