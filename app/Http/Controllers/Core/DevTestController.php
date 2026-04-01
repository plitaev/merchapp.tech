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
        $result = [];

        $res = Pay::with('bot_user')->where('status', 1)->get();
        foreach ($res as $data) {
            $check = Pay::where('bot_user_id', $data->bot_user_id)->where('status', 1)->where('created_at', $data->created_at)->whereNot('id', $data->id)->first();
            if ($check) {
                $result[] = $data->bot_user->email;
            }
        }

        return $result;

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
