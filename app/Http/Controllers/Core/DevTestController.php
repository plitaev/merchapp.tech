<?php
namespace App\Http\Controllers\Core;

use App\Actions\Core\PaySystemCallback\PaySystemCallbackCreate;
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

        $bot_user_ids = BotUser::select('id')->where('date_end', '>=', date('Y-m-d', time()))->pluck('id')->toArray();

        $refs = Pay::whereIn('bot_user_id', $bot_user_ids)->where('product_id', 28)->get();
        $A = [];
        foreach ($refs as $ref) {
            $check = BotUserPrice::where('bot_user_id', $ref->bot_user_id)->where('product_id', 1)->where('price', 1490)->first();
            if ($check) $A[] = $check->bot_user_id;
        }

        return $A;

        /*
        $bot_users = BotUser::where('date_end', '>=', date('Y-m-d', time()))->get();
        foreach ($bot_users as $bot_user) {
            $ban = TelegramBanScheduleLogs::where('bot_user_id', $bot_user->id)->where('status', 1)->orderByDesc('created_at')->first();

            if ($ban) {
                $pay = Pay::where('bot_user_id', $bot_user->id)->where('status', 1)->where('created_at', '>=', $ban->created_at)->orderBy('created_at')->first();
                if ($pay) BotUser::where('id', $bot_user->id)->update(['date_start' => date('Y-m-d', strtotime($pay->created_at))]);
            } else {
                $pay = Pay::where('bot_user_id', $bot_user->id)->where('status', 1)->orderBy('created_at')->first();
                if ($pay) BotUser::where('id', $bot_user->id)->update(['date_start' => date('Y-m-d', strtotime($pay->created_at))]);
            }

        }
        */

    }

}
