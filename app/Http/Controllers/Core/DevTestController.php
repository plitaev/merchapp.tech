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

        $recurrents_all = BotUser::select('date_end')->where('date_end', '>=', date('Y-m', time())."-01")->where('recurrent', 1)->get();
        foreach ($recurrents_all as $recurrent_all) {
            $Amys_users[date("m.Y", strtotime($recurrent_all->date_end))][] = 1;
        }

        return $Amys_users['12.2025'];
    }

    public function paycounts() {

    }

}
