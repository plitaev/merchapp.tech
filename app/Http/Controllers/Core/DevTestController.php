<?php
namespace App\Http\Controllers\Core;
use App\Actions\Core\DateEnd\DateEnd;
use App\Actions\Core\GetCourseWebhook\GetCourseWebhookCreate;
use App\Actions\Core\Telegram\TelegramChatJoinRequest;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\HMACController;
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
        //$bot_users = BotUser::select('id')->pluck('id')->toArray();
        //return Pay::whereIn('bot_user_id', $bot_users)->where('status', 1)->where('product_id', 27)->where('created_at', '>=', '2025-10-17 10:00:00')->where('created_at', '<=', '2025-10-17 23:59:59')->whereNotNull('pay_system_payment_method_id')->count();

        $pays = Pay::select('bot_user_id')->where('status', 1)->where('product_id', 27)->pluck('bot_user_id')->toArray();

        // === Не оплатившие из первого дня

        $datetime_start = '2025-10-17 00:00:00';
        $datetime_end = '2025-10-17 23:59:59';

        $bot_users = BotUser::select('id')
            ->where('bot_branch_id', 2)
            ->whereNotIn('id', $pays)
            ->where('updated_at', '>=', $datetime_start)
            ->where('updated_at', '<=', $datetime_end)
            ->pluck('id')
            ->toArray();

        //return $bot_users;

        // === Купившие из первого дня за 150 и не купившие полный

        $pays_full = Pay::select('bot_user_id')->where('status', 1)->whereIn('product_id', [1, 2, 3])->where('created_at', '>=', $datetime_start)->pluck('bot_user_id')->toArray();

        $bot_users = BotUser::select('id')
            ->where('bot_branch_id', 2)
            ->whereNotIn('id', $pays_full)
            ->whereIn('id', $pays_full)
            ->where('updated_at', '>=', $datetime_start)
            ->where('updated_at', '<=', $datetime_end)
            ->pluck('id')
            ->toArray();

        return $bot_users;

    }
}
