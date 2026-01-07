<?php
namespace App\Http\Controllers\Core;

use Carbon\Carbon;

use App\Http\Controllers\Controller;

use App\Models\Core\BotUser;
use App\Models\Core\BotUserBanSchedule;
use App\Models\Core\BotUserPrice;
use App\Models\Core\Product;
use Illuminate\Support\Facades\DB;
use Telegram\Bot\Api;

use App\Models\Core\Pay;

use App\Actions\Core\DateEnd\DateEndNew;

class DevTestController extends Controller
{
    public function devtest() {

        return Carbon::parse('2025-08-24 00:00:00')->addDays(151)->format('d.m.Y');

        $dateEndNew = new DateEndNew();

        $alldays = Pay::select('days', 'created_at', 'updated_at')->where('bot_user_id', 1)->orderBy('created_at')->get();

        $result = [];

        foreach ($alldays as $allday) {
            $result[] = $allday->created_at.' - '.$allday->created_at->addDays($allday->days)->subDays(1);
        }

        return $result;

        /*
        $bot_users = BotUser::where('run_status', 0)->get();
        foreach ($bot_users as $bot_user) {
            $dateEndNew->handle($bot_user, 'Y-m-d');
            BotUser::where('id', $bot_user->id)->update(['run_status' => 1]);
        }
        */

        //$bot_users = BotUser::all();
        //return view('core.devtest.devtest', ['bot_users' => $bot_users]);

    }
}
