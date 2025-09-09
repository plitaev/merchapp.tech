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
        $telegram = new Api('8307593800:AAF5THy4VstNdji3U4oF01zRBfKuA74QC9E');

        $res = BotUser::whereNotNull('email')->get();

        $members = [];
        $others = [];

        foreach ($res as $data) {

            $status = $telegram->getChatMember(['chat_id' => -1001288663452, 'user_id' => $data->telegram_chat_id]);
            $status = $status->status;

            if ($status == 'member') {
                $members[] = $data->id;
            } else {
                $others[] = $data->id;
            }

            /*
            TelegramSendMessageSchedule::create(
                [
                    'sending_id' => 2,
                    'bot_user_id' => $data->id
                ]
            );
            */
        }

        foreach ($members as $bot_user_id) {
            TelegramSendMessageSchedule::create(
                [
                    'sending_id' => 3,
                    'bot_user_id' => $bot_user_id
                ]
            );
        }

        foreach ($others as $bot_user_id) {
            TelegramSendMessageSchedule::create(
                [
                    'sending_id' => 4,
                    'bot_user_id' => $bot_user_id
                ]
            );
        }


    }
}
