<?php
namespace App\Http\Controllers\Core;

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
        /*
        $dateEndNew = new DateEndNew();

        $bot_users = BotUser::where('run_status', 0)->get();
        foreach ($bot_users as $bot_user) {
            $dateEndNew->handle($bot_user, 'Y-m-d');
            BotUser::where('id', $bot_user->id)->update(['run_status' => 1]);
        }
        */

        $bot_users = BotUser::all();
        return view('devtest', ['bot_users' => $bot_users]);

    }
}
