<?php
namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;

use App\Models\Core\BotUser;
use App\Models\Core\BotUserPrice;
use App\Models\Core\GetcourseWebhookTicket;
use App\Models\Core\Product;
use Illuminate\Support\Facades\DB;
use Telegram\Bot\Api;

use App\Models\Core\Pay;

class DevTestController extends Controller
{
    public function devtest() {

        $bot_users = BotUser::get();
        $A = [];

        foreach ($bot_users as $bot_user) {
            $count = Pay::where('status', 1)->where('bot_user_id', $bot_user->id)->count();
            if ($count >= 5) $A[] = $bot_user->id;
        }

        return $A;

    }

    public function paycounts() {

    }

}
