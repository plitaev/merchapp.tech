<?php
namespace App\Http\Controllers\Core;
use App\Actions\Core\DateEnd\DateEnd;
use App\Actions\Core\Telegram\TelegramChatJoinRequest;
use App\Http\Controllers\Controller;
use App\Models\Core\Bot;
use App\Models\Core\BotUser;
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
        $res = BotUser::whereNotNull('email')->get();

        foreach ($res as $data) {

            TelegramSendMessageSchedule::create(
                [
                    'sending_id' => 2,
                    'bot_user_id' => $data->id
                ]
            );

        }


    }
}
