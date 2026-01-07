<?php
namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;

use App\Models\Core\BotUser;
use App\Models\Core\BotUserBanSchedule;
use App\Models\Core\BotUserPrice;
use App\Models\Core\GetcourseWebhookTicket;
use App\Models\Core\Product;
use Illuminate\Support\Facades\DB;
use Telegram\Bot\Api;

use App\Models\Core\Pay;

class DevTestController extends Controller
{
    public function devtest() {

        $users_td = Pay::select('bot_user_id')->where('product_id', 27)->where('status', 1)->where('created_at', '>=', '2026-01-03 10:00:00')->groupBy('bot_user_id')->pluck('bot_user_id')->toArray();
        $fulls_td = Pay::select('bot_user_id')->whereNot('product_id', 27)->where('status', 1)->whereIn('bot_user_id', $users_td)->where('created_at', '>=', '2026-01-03 10:00:00')->whereNot('days', 1)->groupBy('bot_user_id')->get();
        $olds_for_td = Pay::select('bot_user_id')->where('status', 1)->where('created_at', '<', '2026-01-03 10:00:00')->groupBy('bot_user_id')->pluck('bot_user_id')->toArray();
        $users_td_without_olds = Pay::select('bot_user_id')->where('product_id', 27)->where('status', 1)->where('created_at', '>=', '2026-01-03 10:00:00')->whereNotIn('bot_user_id', $olds_for_td)->groupBy('bot_user_id')->pluck('bot_user_id')->toArray();

        return count($users_td).' - '.count($fulls_td).' - '.count($users_td_without_olds);

    }

    public function paycounts() {

    }

}
