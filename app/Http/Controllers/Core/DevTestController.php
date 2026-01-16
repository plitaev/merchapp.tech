<?php
namespace App\Http\Controllers\Core;

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

use App\Actions\Core\DateEnd\DateEndNew;

class DevTestController extends Controller
{
    public function devtest() {
        $fm = BotUser::select('telegram_chat_id')->where('bot_id', 2)->pluck('telegram_chat_id')->toArray();
        return BotUser::select('telegram_chat_id')->where('bot_id', 25)->whereIn('telegram_chat_id', $fm)->pluck('telegram_chat_id')->toArray();
    }

    public function paycounts() {

    }

}
