<?php

namespace App\Actions\Project\ClubAccess;

use App\Actions\Core\BotSendMessage\BotSendMessage;
use App\Actions\Core\BotUser\BotUserSetEmail;
use App\Actions\Core\BotUser\BotUserSetListener;
use App\Actions\Core\DateEnd\DateEnd;
use App\Actions\Core\Pay\PayCreateByPayGuest;
use App\Models\Core\BotUser;
use App\Models\Core\Pay;
use App\Models\Core\Product;

class BotListenerEmail
{
    public function handle($webhook, $bot_user) {
        $botSendMessage = new BotSendMessage();
        $botUserSetEmail = new BotUserSetEmail();
        $botUserSetListener = new BotUserSetListener();
        $botWrongEmail = new BotWrongEmail();
        $dateEnd = new DateEnd();
        $payCreateByPayGuest = new PayCreateByPayGuest();

        //== Проверяем, ожидает ли юзер ввода почты
        if ($bot_user->listen_email_status == 1) {

            //== Если ожидает, то проверяем, является ли введенная пользователем строка почтой через стандартный определитель ТГ
            if (isset($webhook['message']['entities']) && $webhook['message']['entities'][0]['type']=='email') {

                $other_telegram_user = BotUser::where('email', $webhook['message']['text'])->whereNot('id', $bot_user->id)->count();

                if ($other_telegram_user > 0) {
                    $botSendMessage->handle($bot_user, 'SYS_OTHER_USER_WITH_ENTERED_EMAIL');
                    die();
                }

                //== Если юзер ввёл почту, то привязываем её ему к аккаунту
                $botUserSetEmail->handle($bot_user, $webhook['message']['text']);

                //== Проверяем гостевые платежи и перекачиваем
                $payCreateByPayGuest->handle($bot_user, $webhook['message']['text']);

                if ($bot_user->listen_check_access_status == 1) {
                    $products = Product::select('id')->where('bot_id', $bot_user->bot_id)->pluck('id')->toArray();

                    if (count($products) > 0) {

                        $pays = Pay::where('bot_user_id', $bot_user->id)->whereIn('product_id', $products)->where('status', 1)->count();
                        if ($pays == 0) $pays = Pay::where('gift_bot_user_id', $bot_user->id)->whereIn('product_id', $products)->where('status', 1)->count();

                        if ($pays > 0) {
                            $date_end = $dateEnd->handle($bot_user, 'Y-m-d');
                            if ($date_end > date('Y-m-d', time())) {
                                $botSendMessage->handle($bot_user, 'SYS_SUCCESS_MESSAGE');

                                //== Выключаем листенер почты
                                $botUserSetListener->handle('listen_email', 0, $bot_user->id);

                            } else {
                                $botSendMessage->handle($bot_user, 'SYS_ACCESS_EXPIRED');
                            }

                        } else {
                            $botWrongEmail->handle($bot_user);
                        }

                    } else {
                        $botWrongEmail->handle($bot_user);
                    }

                    die();
                }

                //== Выключаем листенер почты
                $botUserSetListener->handle('listen_email', 0, $bot_user->id);

                if ($bot_user->sys_go_to_pay_status == 1) {
                    $botSendMessage->handle($bot_user, 'SYS_PAY_IN_BOT');
                    die();
                }

            } else {
                //== А если не почта - выкидываем сообщение о неверно введенной почте
                $botSendMessage->handle($bot_user, 'SYS_ENTERED_EMAIL_INCORRECT');
                die();
            }

        }
    }

}
