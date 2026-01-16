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
        $dateEnd = new DateEnd();

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
