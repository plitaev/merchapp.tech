<?php
namespace App\Http\Controllers\Core;

use App\Actions\Core\BotSupergroup\BotSupergroupsAll;
use App\Actions\Core\BotUser\BotUserInsertVariables;
use App\Actions\Core\BotUser\BotUserSetUnbanScheduler;
use App\Actions\Core\BotUser\BotUserUnban;
use App\Actions\Core\BotUserPrice\BotUserPriceGet;
use App\Actions\Core\PaySystemCallback\PaySystemCallbackCreate;
use App\Actions\Core\Product\ProductListByBot;
use App\Actions\Core\Telegram\TelegramWebhookInfo;
use App\Models\Core\BotMessage;
use App\Models\Core\BotMessageButton;
use App\Models\Core\GetcourseWebhook;
use App\Models\Core\TelegramSendMessageErrorLog;
use App\Models\Core\TelegramSendMessageLog;
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

use App\Actions\Core\Max\MaxQuery;

use App\Models\Core\Bot;
use App\Models\Core\MaxSendMessageLog;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\Core\User;
use App\Actions\Core\BotSendMessage\BotSendMessage;

class DevTestController extends Controller
{
    public function devtest() {

        $bot_user = BotUser::find(7874);
        $botSendMessage = new BotSendMessage();

        $api_url = $botSendMessage->handle($bot_user, 'SYS_SUCCESS_MESSAGE', 'telegram');



        //$url = 'https://api-endpoint5.loverse.me/bot7427797340:AAEZd2WfiGalZ7EvAdRv2yCNkgTDwM7nVhY/sendMessage?text=%F0%9F%8C%9F+%3Cb%3E%D0%94%D0%BE%D0%B1%D1%80%D0%BE+%D0%BF%D0%BE%D0%B6%D0%B0%D0%BB%D0%BE%D0%B2%D0%B0%D1%82%D1%8C+%D0%B2+%D0%A7%D0%A3%D0%94%D0%9E+%D0%9A%D0%9B%D0%A3%D0%91+%28ex+Magic+Club%29+%D0%90%D0%BD%D0%B0%D1%81%D1%82%D0%B0%D1%81%D0%B8%D0%B8+%D0%90%D0%BD%D0%B8%D1%81%D0%B8%D0%BC%D0%BE%D0%B2%D0%BE%D0%B9%21%3C%2Fb%3E+%F0%9F%8C%9F%0A%0A%D0%A2%D0%B5%D0%BF%D0%B5%D1%80%D1%8C+%D0%B2%D1%8B+%D1%87%D0%B0%D1%81%D1%82%D1%8C+%D0%BF%D1%80%D0%BE%D1%81%D1%82%D1%80%D0%B0%D0%BD%D1%81%D1%82%D0%B2%D0%B0%2C+%D0%B3%D0%B4%D0%B5+%D1%82%D0%B2%D0%BE%D1%80%D1%87%D0%B5%D1%81%D0%BA%D0%B0%D1%8F+%D1%8D%D0%BD%D0%B5%D1%80%D0%B3%D0%B8%D1%8F%2C+%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D0%B0+%D1%81+%D0%BF%D0%BE%D0%B4%D1%81%D0%BE%D0%B7%D0%BD%D0%B0%D0%BD%D0%B8%D0%B5%D0%BC+%D0%B8+%D0%BF%D0%BE%D0%B4%D0%B4%D0%B5%D1%80%D0%B6%D0%BA%D0%B0+%D1%81%D0%BF%D0%BE%D1%81%D0%BE%D0%B1%D1%81%D1%82%D0%B2%D1%83%D1%8E%D1%82+%3Cb%3E%D0%B4%D0%BE%D1%81%D1%82%D0%B8%D0%B6%D0%B5%D0%BD%D0%B8%D1%8E+%D1%86%D0%B5%D0%BB%D0%B5%D0%B9+%D0%B8+%D1%80%D0%B0%D1%81%D0%BA%D1%80%D1%8B%D0%B2%D0%B0%D1%8E%D1%82+%D0%B2%D0%BD%D1%83%D1%82%D1%80%D0%B5%D0%BD%D0%BD%D0%B8%D0%B9+%D0%BF%D0%BE%D1%82%D0%B5%D0%BD%D1%86%D0%B8%D0%B0%D0%BB.%3C%2Fb%3E%0A%0A%E2%80%BC%EF%B8%8F%3Cb%3E%D0%92%D0%90%D0%96%D0%9D%D0%90%D0%AF+%D0%98%D0%9D%D0%A4%D0%9E%D0%A0%D0%9C%D0%90%D0%A6%D0%98%D0%AF%3C%2Fb%3E+%E2%80%BC%EF%B8%8F%0A%0A%3Cb%3E%D0%9E%D0%B1%D1%8F%D0%B7%D0%B0%D1%82%D0%B5%D0%BB%D1%8C%D0%BD%D0%BE+%D0%BF%D0%BE%D0%B4%D0%BF%D0%B8%D1%88%D0%B8%D1%82%D0%B5%D1%81%D1%8C+%D0%BD%D0%B0+%D0%BE%D0%B1%D0%B0+%D0%BA%D0%B0%D0%BD%D0%B0%D0%BB%D0%B0+%D0%BA%D0%BB%D1%83%D0%B1%D0%B0.+%D0%94%D0%BB%D1%8F+%D1%8D%D1%82%D0%BE%D0%B3%D0%BE+%D0%BD%D0%B0%D0%B6%D0%BC%D0%B8%D1%82%D0%B5+%D0%BD%D0%B0+%D0%BA%D0%BD%D0%BE%D0%BF%D0%BA%D0%B8+%D0%BD%D0%B8%D0%B6%D0%B5%3C%2Fb%3E%F0%9F%91%87%0A%0A%D0%9E%D0%B1%D1%80%D0%B0%D1%82%D0%B8%D1%82%D0%B5+%D0%B2%D0%BD%D0%B8%D0%BC%D0%B0%D0%BD%D0%B8%D0%B5+%D0%BD%D0%B0+%D1%82%D0%BE%2C+%D1%87%D1%82%D0%BE+%D0%B8%D1%81%D1%82%D0%BE%D1%80%D0%B8%D1%8F+%D0%BA%D0%B0%D0%BD%D0%B0%D0%BB%D0%B0+%D1%81%D0%BA%D1%80%D1%8B%D1%82%D0%B0+%D0%B4%D0%BB%D1%8F+%D1%82%D0%BE%D0%BB%D1%8C%D0%BA%D0%BE+%D0%B2%D0%BE%D1%88%D0%B5%D0%B4%D1%88%D0%B8%D1%85+%D0%BF%D0%BE%D0%BB%D1%8C%D0%B7%D0%BE%D0%B2%D0%B0%D1%82%D0%B5%D0%BB%D0%B5%D0%B9.+%D0%92%D1%8B+%D0%B1%D1%83%D0%B4%D0%B5%D1%82%D0%B5+%D0%B2%D0%B8%D0%B4%D0%B5%D1%82%D1%8C+%D1%82%D0%BE%D0%BB%D1%8C%D0%BA%D0%BE+%D1%82%D0%B5+%D0%B7%D0%B0%D0%BF%D0%B8%D1%81%D0%B8%2C+%D0%BA%D0%BE%D1%82%D0%BE%D1%80%D1%8B%D0%B5++%D0%B2%D1%8B%D1%81%D1%82%D0%B0%D0%B2%D0%B8%D0%BB%D0%B8+%D0%B2+%D0%BA%D0%B0%D0%BD%D0%B0%D0%BB+%D0%BF%D0%BE%D1%81%D0%BB%D0%B5+%D1%82%D0%BE%D0%B3%D0%BE%2C+%D0%BA%D0%B0%D0%BA+%D0%B2%D1%8B+%D0%BF%D0%BE%D0%B4%D0%BF%D0%B8%D1%81%D0%B0%D0%BB%D0%B8%D1%81%D1%8C+%D0%BD%D0%B0+%D0%BD%D0%B5%D0%B3%D0%BE.%0A%0A%3Cb%3E%D0%92%D0%B0%D1%88+%D0%B4%D0%BE%D1%81%D1%82%D1%83%D0%BF+%D0%B0%D0%BA%D1%82%D0%B8%D0%B2%D0%B5%D0%BD+%D0%B4%D0%BE+11.05.2026+%28%D0%B4%D0%BE+23%3A59+%D0%9C%D0%A1%D0%9A+%D0%B2%D0%BA%D0%BB%D1%8E%D1%87%D0%B8%D1%82%D0%B5%D0%BB%D1%8C%D0%BD%D0%BE%21%29%3C%2Fb%3E%0A%0A%3Cb%3E%D0%92%D0%B0%D1%88+E-mail%3A+evgeniiplita%40gmail.com%3C%2Fb%3E%0A%0A%D0%95%D1%81%D0%BB%D0%B8+%D0%B2%D1%8B+%D0%B7%D0%B0%D1%85%D0%BE%D1%82%D0%B8%D1%82%D0%B5%2C+%D0%B2%D1%8B+%D0%BC%D0%BE%D0%B6%D0%B5%D1%82%D0%B5+%D0%BF%D1%80%D0%BE%D0%B4%D0%BB%D0%B8%D1%82%D1%8C+%D0%B2%D0%B0%D1%88%D1%83+%D0%BF%D0%BE%D0%B4%D0%BF%D0%B8%D1%81%D0%BA%D1%83%2C+%D0%BD%D0%B5+%D0%B4%D0%BE%D0%B6%D0%B8%D0%B4%D0%B0%D1%8F%D1%81%D1%8C+%D0%BE%D0%BA%D0%BE%D0%BD%D1%87%D0%B0%D0%BD%D0%B8%D1%8F+%D1%81%D1%80%D0%BE%D0%BA%D0%B0.+%D0%9E%D0%BF%D0%BB%D0%B0%D1%87%D0%B5%D0%BD%D0%BD%D1%8B%D0%B9+%D0%BF%D0%B5%D1%80%D0%B8%D0%BE%D0%B4+%D0%B1%D1%83%D0%B4%D0%B5%D1%82+%D0%B4%D0%BE%D0%B1%D0%B0%D0%B2%D0%BB%D0%B5%D0%BD+%D0%BA+%D1%82%D0%B5%D0%BA%D1%83%D1%89%D0%B5%D0%BC%D1%83.%0A%0A%D0%92%D0%B0%D1%88+%D0%B4%D0%BE%D1%81%D1%82%D1%83%D0%BF+%D0%BA+%D0%B2%D0%B5%D0%B1-%D0%B2%D0%B5%D1%80%D1%81%D0%B8%D0%B8%3A%0A%0A%D0%A1%D1%81%D1%8B%D0%BB%D0%BA%D0%B0%3A+%3Ca+href%3D%22https%3A%2F%2Fclub.formagic.ru%22%3E%3Cb%3Ehttps%3A%2F%2Fclub.formagic.ru%3C%2Fb%3E%3C%2Fa%3E%0A%0A%D0%9B%D0%BE%D0%B3%D0%B8%D0%BD%3A+evgeniiplita%40gmail.com%0A%0A%D0%9F%D0%B0%D1%80%D0%BE%D0%BB%D1%8C%3A+mWUDHO5Z&chat_id=247632034&reply_markup={"inline_keyboard":[[{"text":"\u0427\u0423\u0414\u041e \u041a\u041b\u0423\u0411 \u0410\u043d\u0430\u0441\u0442\u0430\u0441\u0438\u0438 \u0410.","url":"https:\/\/t.me\/+EqMcTP1cCapiMzAy"}],[{"text":"\u0417\u0430\u043f\u0438\u0441\u0438 \u0432\u0441\u0442\u0440\u0435\u0447 NEW","url":"https:\/\/t.me\/+cZhLSBwqAv8zMzgy"}],[{"text":"\u041e\u0442\u043a\u0440\u044b\u0442\u044c \u0432\u0435\u0431-\u0432\u0435\u0440\u0441\u0438\u044e","url":"https:\/\/club.formagic.ru"}]]}&parse_mode=HTML&protect_content=';
        $url = 'https://api-endpoint5.loverse.me/bot7427797340:AAEZd2WfiGalZ7EvAdRv2yCNkgTDwM7nVhY/sendMessage?text=🌟 Добро пожаловать в ЧУДО КЛУБ (ex Magic Club) Анастасии Анисимовой! 🌟 Теперь вы часть пространства, где творческая энергия, работа с подсознанием и поддержка способствуют достижению целей и раскрывают внутренний потенциал. ‼️ВАЖНАЯ ИНФОРМАЦИЯ ‼️ Обязательно подпишитесь на оба канала клуба. Для этого нажмите на кнопки ниже👇 Обратите внимание на то, что история канала скрыта для только вошедших пользователей. Вы будете видеть только те записи, которые выставили в канал после того, как вы подписались на него. Ваш доступ активен до 11.05.2026 (до 23:59 МСК включительно!) Ваш E-mail: evgeniiplita@gmail.com Если вы захотите, вы можете продлить вашу подписку, не дожидаясь окончания срока. Оплаченный период будет добавлен к текущему. Ваш доступ к веб-версии: Ссылка: https://club.formagic.ru Логин: evgeniiplita@gmail.com Пароль: mWUDHO5Z&chat_id=247632034';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        $response = curl_exec($curl);

        if(curl_errno($curl)){
            echo 'Ошибка Curl: ' . curl_error($curl);
        }

        curl_close($curl);

        return 'responce: '.$response;
    }

    public function change_web_password(string $email) {
        $botSendMessage = new BotSendMessage();

        $user = User::where('email', $email)->first();
        if ($user) {
            $plainPassword = Str::password(8, true, true, false, false);
            $hashedPassword = Hash::make($plainPassword);

            User::where('id', $user->id)->update(['password' => $hashedPassword, 'open_password' => $plainPassword]);

            $bot_user = BotUser::where('email', $email)->first();
            if ($bot_user) {
                $botSendMessage->handle($bot_user, 'MAGICLIFE_WEB_ACCESS');
            }

            return $plainPassword;
        } else {
            return "Нет юзера с этой почтой";
        }

    }

}
