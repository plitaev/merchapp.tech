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
        $elki = BotUser::select('id')->where('bot_id', 25)->whereIn('telegram_chat_id', $fm)->pluck('id')->toArray();
        $elki_products_fm = Pay::whereIn('bot_user_id', $elki)->whereIn('product_id', [6, 9, 10, 22,23])->get();
        return $elki_products_fm;
    }

    public function paycounts() {

    }

}
