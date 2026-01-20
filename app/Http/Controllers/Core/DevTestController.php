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

class DevTestController extends Controller
{
    public function devtest() {
        $ids = Pay::select('bot_user_id')->whereIn('product_id', [2, 3, 10, 25])->where('status', 1)->pluck('bot_user_id')->toArray();
        $bot_users = BotUser::where('date_end', '>=', date('Y-m-d', time()))->whereIn('id', $ids)->get();
        return $bot_users;
    }

    public function paycounts() {

    }

}
