<?php
namespace App\Http\Controllers\Core;
use App\Actions\Core\DateEnd\DateEnd;
use App\Actions\Core\Telegram\TelegramChatJoinRequest;
use App\Http\Controllers\Controller;
use App\Models\Core\Bot;
use App\Models\Core\BotUser;
use App\Models\Core\BotUserRecurrentSchedule;
use App\Models\Core\Pay;
use App\Models\Core\Product;
use App\Models\Core\TelegramSendMessageSchedule;
use App\Models\Core\TelegramWebhook;
use App\Models\Core\Sending;

use App\Actions\Core\BotSendMessage\BotSendMessage;

use YooKassa\Client;
use Telegram\Bot\Api;

use App\Models\Core\TelegramChatJoinRequestLog;

class DevTestController extends Controller
{
    public function devtest() {
        $recurrents = BotUserRecurrentSchedule::select('new_pay_id')->pluck('new_pay_id')->toArray();
        Pay::whereNotIn('id', $recurrents)->update(['recurrent' => 0, 'recurrent_status' => 0]);
        Pay::whereIn('id', $recurrents)->update(['recurrent' => 1]);
        Pay::whereIn('id', $recurrents)->where('status', 1)->update(['recurrent' => 1, 'recurrent_status' => 0]);
        Pay::whereIn('id', $recurrents)->where('status', 1)->update(['recurrent' => 1, 'recurrent_status' => 1]);
    }
}
