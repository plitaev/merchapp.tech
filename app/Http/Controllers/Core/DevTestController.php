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

        $date_end = new DateEnd();
        $bot_users = BotUser::get();
        foreach ($bot_users as $bot_user) {
            $date_end_result = $date_end->handle($bot_user, 'Y-m-d');

            if ($date_end != $bot_user->date_end || !isset($bot_user->date_end)) {
                BotUser::where('id', $bot_user->id)->update(['date_end' => $date_end_result]);
            }
        }

        //$emails = BotUser::select('email')->whereNotNull('email')->pluck('email')->toArray();
        //return GetCourseWebhook::select('email')->where('created_at', '>=', '2025-10-04 00:00:00')->whereNotIn('email', $emails)->groupBy('email')->orderBy('email')->pluck('email')->toArray();
    }
}
