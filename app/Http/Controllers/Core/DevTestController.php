<?php
namespace App\Http\Controllers\Core;

use App\Actions\Core\PaySystemCallback\PaySystemCallbackCreate;
use App\Models\Core\BotMessageButton;
use App\Models\Core\GetcourseWebhook;
use App\Models\Core\TelegramSendMessageSchedule;
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
use App\Models\Core\PaySystemCallback;

use App\Actions\Core\DateEnd\DateEndNew;
use App\Actions\Core\DateEnd\DateEnd;
use App\Models\Core\TelegramBanScheduleLogs;

class DevTestController extends Controller
{
    public function devtest() {

        $bot_message_id = 7;

        $res = BotMessageButton::where('bot_message_id', $bot_message_id)->orderBy('pos')->get();
        $last_pos = 0;

        $k = [];
        $v = [];

        foreach ($res as $data) {
            $k[] = $data->pos;
            $v[] = $data->pos." - ".$data->name;
            $last_pos = $data->pos;
        }

        if ($bot_message_id == 0) {
            $next_pos = $last_pos + 1;
            $k[] = $next_pos;
            $v[] = $next_pos.' - Новая кнопка';
        } else {
            $next_pos = 1;
        }

        $result = array_combine($k, $v);

        return [$result, $next_pos];

    }

}
