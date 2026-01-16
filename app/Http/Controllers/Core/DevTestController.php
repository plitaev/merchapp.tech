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
        $pays = Pay::whereHas('bot_user', function ($query) {
            $query->where('bot_id', 25);
        })->whereIn('product_id', [6, 9, 10, 22, 23])->get();

        $not_found = [];

        foreach ($pays as $pay) {
            $right_user = BotUser::where('email', $pay->bot_user->email)->where('bot_id', 2)->first();
            if ($right_user) {

            } else {
                $not_found[] = $pay->created_at." - ".$pay->days;
            }
        }

        return $not_found;
    }

    public function paycounts() {

    }

}
