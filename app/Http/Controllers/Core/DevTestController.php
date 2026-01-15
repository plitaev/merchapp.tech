<?php
namespace App\Http\Controllers\Core;

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

use App\Actions\Core\DateEnd\DateEndNew;

class DevTestController extends Controller
{
    public function devtest() {
        /*
        Pay::where('product_id', 11)->update(['product_id' => 10]);
        Pay::where('product_id', 12)->update(['product_id' => 10]);
        Pay::where('product_id', 13)->update(['product_id' => 10]);
        Pay::where('product_id', 14)->update(['product_id' => 10]);
        Pay::where('product_id', 15)->update(['product_id' => 10]);
        Pay::where('product_id', 16)->update(['product_id' => 10]);
        Pay::where('product_id', 17)->update(['product_id' => 10]);
        Pay::where('product_id', 18)->update(['product_id' => 10]);
        Pay::where('product_id', 19)->update(['product_id' => 10]);
        Pay::where('product_id', 20)->update(['product_id' => 10]);
        Pay::where('product_id', 21)->update(['product_id' => 10]);

        GetcourseWebhook::where('product_id', 11)->update(['product_id' => 10]);
        GetcourseWebhook::where('product_id', 12)->update(['product_id' => 10]);
        GetcourseWebhook::where('product_id', 13)->update(['product_id' => 10]);
        GetcourseWebhook::where('product_id', 14)->update(['product_id' => 10]);
        GetcourseWebhook::where('product_id', 15)->update(['product_id' => 10]);
        GetcourseWebhook::where('product_id', 16)->update(['product_id' => 10]);
        GetcourseWebhook::where('product_id', 17)->update(['product_id' => 10]);
        GetcourseWebhook::where('product_id', 18)->update(['product_id' => 10]);
        GetcourseWebhook::where('product_id', 19)->update(['product_id' => 10]);
        GetcourseWebhook::where('product_id', 20)->update(['product_id' => 10]);
        GetcourseWebhook::where('product_id', 21)->update(['product_id' => 10]);
        */

        $bot_users = BotUser::all();
        foreach ($bot_users as $bot_user) {
            $products = Product::select('id')->where('bot_id', $bot_user->bot_id)->pluck('id')->toArray();
            BotUser::where('bot_user_id', $bot_user->id)->whereNotIn('product_id', $products)->delete();
        }

    }

    public function paycounts() {

    }

}
