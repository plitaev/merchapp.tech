<?php
namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;

use App\Models\Core\BotUser;
use App\Models\Core\BotUserPrice;
use App\Models\Core\Product;
use Illuminate\Support\Facades\DB;
use Telegram\Bot\Api;

use App\Models\Core\Pay;

class DevTestController extends Controller
{
    public function devtest() {
        $res = Pay::where('pay_system_id', 2)->where('status', 1)->whereNotNull('pay_system_callback')->orderBy('created_at')->get();
        foreach ($res as $data) {
            $A = json_decode($data->pay_system_callback, true);
            if (isset($A['maskedPan'])) {
                BotUser::where('id', $data->bot_user_id)->update(['card_mask' => $A['maskedPan']]);
            }
        }
    }
}
