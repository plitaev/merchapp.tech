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

        $recurrent = 1;

        $recurrents_all = BotUser::select('date_end')->where('date_end', '>=', date('Y-m', time())."-01")->where('recurrent', $recurrent)->get();
        foreach ($recurrents_all as $recurrent_all) {
            $Amys_users[date("m.Y", strtotime($recurrent_all->date_end))][] = 1;
        }

        $recurrent_dates = BotUser::select('date_end')->where('date_end', '>=', date('Y-m', time())."-01")->where('recurrent', $recurrent)->groupBy('date_end')->orderBy('date_end')->pluck('date_end')->toArray();
        foreach ($recurrent_dates as $recurrent_date) {
            $Amys[] = date("m.Y", strtotime($recurrent_date));
        }

        $Amys = array_unique($Amys);

        return $Amys;
    }

    public function paycounts() {

    }

}
