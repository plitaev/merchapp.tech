<?php
namespace App\Http\Controllers\Core;

use App\Actions\Core\PaySystemCallback\PaySystemCallbackCreate;
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
        $telegram = new Api('7427797340:AAEZd2WfiGalZ7EvAdRv2yCNkgTDwM7nVhY');

        $chat_id = 1288080888;

        $caption = '🆘 <b>КЛУБнички! Нам нужна ваша помощь!</b>%0A%0AПомогите протестировать приложение для клуба.%0A%0A❗️ Это ПРОБНАЯ версия. После теста оно будет удалено.%0A%0AПолная версия уже собирается, видео загружаются на серверы и через несколько дней доступ появится у всех участников Клуба%0A%0AЧто важно сейчас:%0A%0A1️⃣ Запустить приложение по этой ссылке:%0A<a href="https://t.me/ChudoClubVideoTestBot?startapp%0A%0A">https://t.me/ChudoClubVideoTestBot?startapp%0A%0A</a>2️⃣ Перейти в оба раздела и попробовать запустить видео%0A%0A3️⃣ Оставить отзыв о том, всё ли было понятно, как работает приложение и что работает или НЕ РАБОТАЕТ. %0AДля этого нажмите%0A👇👇👇👇%0A%0A<a href="https://t.me/+VTYfEQWOv8YyNGJi">ОСТАВИТЬ ОТЗЫВ</a>';
        $caption = urldecode($caption);

        $keyboard=["inline_keyboard"=>[
            [["text" => "НАЧАТЬ ТЕСТИРОВАНИЕ", "url" => "https://t.me/ChudoClubVideoTestBot?startapp"]],
            [["text" => "ОСТАВИТЬ ОТЗЫВ", "url" => "https://t.me/+VTYfEQWOv8YyNGJi"]]
        ]];
        $keyboard=json_encode($keyboard,true);

        $media = array(
            array(
                'type' => 'photo',
                'media' => 'https://0daafeb7-af8a-406f-95cc-f9618e814376.selstorage.ru/oooo/1.jpg',
                'caption' => $caption,
                'parse_mode' => 'HTML',
                'reply_markup' => json_encode($keyboard)
            ),
            array(
                'type' => 'photo',
                'media' => 'https://0daafeb7-af8a-406f-95cc-f9618e814376.selstorage.ru/oooo/2.jpg',
            ),
            array(
                'type' => 'photo',
                'media' => 'https://0daafeb7-af8a-406f-95cc-f9618e814376.selstorage.ru/oooo/3.jpg',
            ),
            array(
                'type' => 'photo',
                'media' => 'https://0daafeb7-af8a-406f-95cc-f9618e814376.selstorage.ru/oooo/4.jpg',
            ),
        );


        $A = [
            'chat_id' => $chat_id,
            'media' => json_encode($media),
        ];

        $telegram->sendMediaGroup($A);

        //===

        $A = [
            'chat_id' => $chat_id,
            'text' => urldecode('Ваша помощь будет бесценна ❤️'),
            'reply_markup' => $keyboard
        ];

        $telegram->sendMessage($A);

    }

}
