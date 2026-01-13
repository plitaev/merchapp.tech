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

        $bot_users = BotUser::where('date_end', '>=', date('Y-m-d', time()))->get();
        $A = [];
        foreach ($bot_users as $bot_user) {
            $pay = Pay::where('bot_user_id', $bot_user->id)->where('status', 1)->whereIn('product_id', [2, 3])->get();
            if ($pay) {
                if (count($pay) > 0) {
                    $A[] = $bot_user->id;
                }
            }
        }

        $bot_users = BotUser::whereIn('id', $A)->get();

        return view('core.devtest.devtest', ['bot_users' => $bot_users]);

        /*
        $date_end_new = new DateEndNew();

        $bot_users = BotUser::where('run_status', 0)->get();
        foreach ($bot_users as $bot_user) {
            $date_end_new->handle($bot_user, 'Y-m-d');
            BotUser::where('id', $bot_user->id)->update(['run_status' => 1]);
        }
        */
        /*
        $bot_users = BotUser::where('date_end', '>=', date('Y-m-d', time()))->get();
        foreach ($bot_users as $bot_user) {
            BotUserPrice::create(['bot_user_id' => $bot_user->id, 'product_id' => 6, 'price' => 990]);
        }
        */

    }

    public function paycounts() {

    }

}
