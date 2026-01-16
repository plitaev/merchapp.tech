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
use App\Models\Core\PayGuest;

use App\Actions\Core\DateEnd\DateEndNew;
use App\Actions\Core\DateEnd\DateEnd;

class DevTestController extends Controller
{
    public function devtest() {
        /*
        $dateEndNew = new DateEndNew();

        $bot_users = BotUser::where('run_status', 0)->get();
        foreach ($bot_users as $bot_user) {
            $dateEndNew->handle($bot_user, 'Y-m-d');
            BotUser::where('id', $bot_user->id)->update(['run_status' => 1]);
        }
        */
        /*
        $result = [];

        $bot_users = BotUser::whereNotNull('date_end')->whereNotNull('date_end_new')->where('date_end', '>=', date('Y-m-d H:i:s', time()))->where('run_status', 0)->get();
        foreach ($bot_users as $bot_user) {
            BotUser::where('id', $bot_user->id)->update(['run_status' => 1]);

            $diff = Carbon::parse($bot_user->date_end_new)->diffInDays($bot_user->date_end);

            if ($diff > 0) {
                $new = \App\Models\Core\Pay::insert([
                    'product_id' => 29,
                    'gift' => 0,
                    'bot_user_id' => $bot_user->id,
                    'price' => 0,
                    'days' => $diff,
                    'status' => 1,
                    'recurrent' => 0,
                    'recurrent_status' => 0,
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'updated_at' => date('Y-m-d H:i:s', time())
                ]);
            }

            $result[] = $bot_user->id.' > '.$diff;
        }

        return $result;
        */
        /*
        $dateEnd = new DateEnd();

        $bot_users = BotUser::where('run_status', 0)->get();
        foreach ($bot_users as $bot_user) {
            $dateEnd->handle($bot_user, 'Y-m-d');
            BotUser::where('id', $bot_user->id)->update(['run_status' => 1]);
        }
        */

        /*
         * KOLCHUKI
         *
        $pays = Pay::whereHas('bot_user', function ($query) {
            $query->where('bot_id', 25);
        })->whereIn('product_id', [6, 9, 10, 22, 23])->get();

        $not_found = [];

        foreach ($pays as $pay) {
            $right_user = BotUser::where('email', $pay->bot_user->email)->where('bot_id', 2)->first();
            if ($right_user) {
                Pay::where('id', $pay->id)->update(['bot_user_id' => $right_user->id]);
                $dateEnd->handle($right_user, 'Y-m-d');
                $dateEnd->handle($pay->bot_user, 'Y-m-d');
            } else {
                $not_found[] = $pay->created_at." - ".$pay->days;

                $new = PayGuest::insert([
                    'product_id' => $pay->product_id,
                    'email' => $pay->bot_user->email,
                    'price' => $pay->price,
                    'days' => $pay->days,
                    'gift' => 0,
                    'status' => 0,
                    'recurrent' => 0,
                    'recurrent_status' => 0,
                    'created_at' => $pay->created_at,
                    'updated_at' => $pay->updated_at
                ]);

                Pay::destroy($pay->id);
                $dateEnd->handle($pay->bot_user, 'Y-m-d');
            }
        }

        return $not_found;
        */



    }

    public function paycounts() {

    }

}
