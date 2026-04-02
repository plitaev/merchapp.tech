<?php
namespace App\Http\Controllers\Core;

use App\Actions\Core\BotSupergroup\BotSupergroupsAll;
use App\Actions\Core\BotUser\BotUserUnban;
use App\Actions\Core\PaySystemCallback\PaySystemCallbackCreate;
use App\Models\Core\BotMessageButton;
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

use App\Actions\Core\Max\MaxQuery;

use App\Models\Core\Bot;

class DevTestController extends Controller
{
    public function devtest() {

        $bot_users = BotUser::where('bot_id', 8)->get();
        foreach ($bot_users as $bot_user) {
            BotUserBanSchedule::create(
                [
                    'bot_user_id' => $bot_user->id,
                    'run_status' => 0,
                    'ban_datetime' => '2026-04-02 08:00:00'
                ]
            );
        }

        /*
        $date_end = new DateEnd();

        $pays = Pay::where('payed_at', '2025-10-03 09:53:29')->get();

        foreach ($pays as $pay) {
            Pay::where('id', $pay->id)->update(['payed_at' => $pay->created_at]);
        }

        $bot_users = BotUser::get();
        foreach ($bot_users as $bot_user) {
            $date_end->handle($bot_user, 'Y-m-d');
        }
        */
        /*
        $pays = Pay::with('bot_user')
            ->where('status', 1)
            ->where('created_at', '>=', '2026-02-26 00:00:00')
            ->where('created_at', '<=', '2026-03-26 23:59:59')
            ->orderByDesc('created_at')
            ->get();

        return view('core.devtest.devtest', ['pays' => $pays]);
        */
    }
}
