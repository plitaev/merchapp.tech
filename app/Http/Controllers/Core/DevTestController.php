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

        $res = BotUser::where('bot_id', 3)->where('ban', 0)->where('date_end', '<', '2026-01-03')->get();
        foreach ($res as $data) {
            BotUserBanSchedule::create(
                [
                    'bot_user_id' => $data->id,
                    'run_status' => 0,
                    'ban_datetime' => '2026-01-03 07:28:00'
                ]
            );
        }

    }

    public function paycounts() {

    }

}
