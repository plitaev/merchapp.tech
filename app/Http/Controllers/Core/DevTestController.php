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
        /*
        $telegram = new Api('8440878720:AAHrI6jj_V16gYrNxKBHnpC_fW835c9nlfU');

        $telegram->deleteMessage(['chat_id' => -1002826769152, 'message_id' => 196]);

        $kb = [];
        $btn = [["text" => "ОТКРЫТЬ ПРИЛОЖЕНИЕ", "url" => "https://t.me/tochka_i_club_bot?startapp"]];
        $kb[] = $btn;

        $keyboard = ["inline_keyboard" => $kb];
        $keyboard = json_encode($keyboard, true);


        $A['chat_id'] = -1002826769152;
        $A['parse_mode'] = 'HTML';
        $A['protect_content'] = true;
        $A['reply_markup'] = $keyboard;
        $A['text'] = urldecode("В этом приложении вы найдете все методички и материалы клуба%0A%0AВСЁ В ОДНОМ МЕСТЕ ❤️");

        return $telegram->sendMessage($A);
        */
    }
}
