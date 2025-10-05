<?php
namespace App\Http\Controllers\Core;
use App\Actions\Core\DateEnd\DateEnd;
use App\Actions\Core\GetCourseWebhook\GetCourseWebhookCreate;
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
use App\Models\Core\GetcourseWebhook;

class DevTestController extends Controller
{
    public function devtest() {
        $result = [];

        $telegram = new Api('7427797340:AAEZd2WfiGalZ7EvAdRv2yCNkgTDwM7nVhY');

        $res = BotUser::where('date_end', '<=', '2025-10-04')->where('ban', 0)->get();

        foreach ($res as $data) {
            $status = $telegram->getChatMember(['chat_id' => -1002225281436, 'user_id' => $data->telegram_chat_id]);
            if ($status->status == 'member') {
                $result[] = $data->telegram_chat_id;
            }

        }

        return BotUser::whereIn('telegram_chat_id', $result)->where('date_end', '<=', '2025-10-04')->get();

    }
}
