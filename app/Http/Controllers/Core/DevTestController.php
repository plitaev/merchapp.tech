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
        /*
        $res = BotUser::where('date_end', '>=', '2025-12-29')->get();
        foreach ($res as $data) {
            $last_pay = Pay::where('bot_user_id', $data->id)->where('status', 1)->orderByDesc('created_at')->first();
            if ($last_pay) {
                BotUserPrice::create(['bot_user_id' => $data->id, 'product_id' => 7, 'price' => $last_pay->price]);
            }
        }
        */
    }

    public function paycounts() {

    }

}
