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

use App\Actions\Core\DateEnd\DateEndNew;
use App\Actions\Core\DateEnd\DateEnd;

class DevTestController extends Controller
{
    public function devtest() {
        $paySystemCallbackCreate = new PaySystemCallbackCreate();

        $res = GetcourseWebhook::skip(0)->take(5000)->get();
        foreach ($res as $data) {
            $A = [
                'product_id' => $data->product_id,
                'getcourse_id' => $data->getcourse_id,
                'name' => $data->name,
                'email' => $data->email,
                'recurrent' => $data->recurrent,
                'recurrent_status' => $data->recurrent_status
            ];
        }

        $paySystemCallbackCreate->handle(json_encode($A), 'getcourse');
    }

    public function paycounts() {

    }

}
