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
        $bot_users = BotUser::select('email')->whereNotNull('email')->pluck('email')->toArray();
        $webhooks = GetCourseWebhook::where('created_at', '>=', '2025-09-20 00:00:00')->whereNotIn('email', $bot_users)->get();

        return view('devtest.devtest', ['webhooks' => $webhooks]);
    }
}
