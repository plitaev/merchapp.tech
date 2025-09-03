<?php
namespace App\Http\Controllers\Core;
use App\Actions\Core\DateEnd\DateEnd;
use App\Actions\Core\Telegram\TelegramChatJoinRequest;
use App\Http\Controllers\Controller;
use App\Models\Core\Bot;
use App\Models\Core\BotUser;
use App\Models\Core\Pay;
use App\Models\Core\Product;
use App\Models\Core\TelegramWebhook;

use App\Actions\Core\BotSendMessage\BotSendMessage;

use YooKassa\Client;
use Telegram\Bot\Api;

use App\Models\Core\TelegramChatJoinRequestLog;

class DevTestController extends Controller
{
    public function devtest() {
        /*
        $date_end = new DateEnd();
        $bot_users = BotUser::whereNotNull('date_end')->get();
        foreach ($bot_users as $bot_user) {
            $date_end->handle($bot_user, 'Y-m-d');
        }
        */
        /*
        $botSendMessage = new BotSendMessage();

        $bot_users = BotUser::whereNull('date_end')->skip(120)->take(50)->get();

        foreach ($bot_users as $bot_user) {
            $botSendMessage->handle($bot_user, 'PROJECT_MAILING_1');
        }
        */
        /*
        $bot = Bot::find($bot_user->bot_id);
        $product = Product::find(7);

        $client = new Client();
        $client->setAuth($bot->yookassa_shop_id, $bot->yookassa_shop_secret);

        return $client->getPaymentInfo("3035311b-000f-5001-9000-1b771a469f1d");
        */
    }
}
