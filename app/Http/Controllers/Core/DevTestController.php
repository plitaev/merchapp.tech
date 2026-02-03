<?php
namespace App\Http\Controllers\Core;

use App\Actions\Core\PaySystemCallback\PaySystemCallbackCreate;
use App\Models\Core\GetcourseWebhook;
use App\Models\Core\TelegramSendMessageSchedule;
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
use App\Models\Core\PayGuest;
use App\Models\Core\PaySystemCallback;

use App\Actions\Core\DateEnd\DateEndNew;
use App\Actions\Core\DateEnd\DateEnd;
use App\Models\Core\TelegramBanScheduleLogs;

class DevTestController extends Controller
{
    public function devtest() {

        BotUser::where('bot_id', 4)->update(['date_end' => '2026-02-03']);

        $res = BotUser::where('bot_id', 4)->get();
        foreach ($res as $data) {
            BotUserBanSchedule::create(['bot_user_id' => $data->bot_user_id, 'run_status' => 0, 'ban_datetime' => '2026-02-03 05:00:00']);
        }

        /*
        $ids = Pay::select('bot_user_id')->where('status', 1)->whereIn('product_id', [3, 25])->where('created_at', '>=', '2025-01-28 00:00:00')->pluck('bot_user_id')->toArray();
        $bot_users = BotUser::select('id')->whereIn('id', $ids)->where('date_end', '>=', '2026-01-28')->pluck('id')->toArray();
        $insds = TelegramSendMessageSchedule::select('bot_user_id')->where('sending_id', 3933)->whereNotIn('bot_user_id', $ids)->get();
        return BotUser::select('email')->whereIn('id', $insds)->pluck('email')->toArray();
        */
        /*
        $pays = Pay::with('bot_user')
            ->where('status', 1)
            ->where('price', '>', 0)
            ->where('created_at', '>=', '2025-12-25 00:00:00')
            ->where('created_at', '<=', '2026-01-26 23:59:59')
            ->orderByDesc('created_at')
            ->get();

        return view('core.devtest.devtest', ['pays' => $pays]);
        */
    }

}
