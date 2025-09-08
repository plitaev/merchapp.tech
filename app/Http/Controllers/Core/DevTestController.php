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
        $sending = Sending::create(
            [
                'bot_id' => 1,
                'bot_message_id' => 16,
                'name' => 'Уже завтра встретимся с тобой в RENEW! Не пропусти💙',
                'send_date' => '2025-09-08',
                'send_time' => '09:00:00'
            ]
        );

        TelegramSendMessageSchedule::create(
            [
                'sending_id' => $sending->id,
                'bot_user_id' => 1,
                'chat_id' => 247632034
            ]
        );

    }
}
