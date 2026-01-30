<?php
namespace App\Http\Controllers\Core;

use App\Actions\Core\PaySystemCallback\PaySystemCallbackCreate;
use App\Models\Core\BotUserUnbanSchedule;
use App\Models\Core\GetcourseWebhook;
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

        $bot_users = BotUser::select('id')->where('date_end', '>=', date('Y-m-d', time()))->where('ban', 1)->get();
        foreach ($bot_users as $bot_user) {
            BotUserUnbanSchedule::create([
                'bot_user_id' => $bot_user->id,
                'run_status' => 0,
                'unban_datetime' => date('Y-m-d H:i:s', time())
            ]);
        }

    }

}
