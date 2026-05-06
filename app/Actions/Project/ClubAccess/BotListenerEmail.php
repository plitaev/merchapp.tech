<?php

namespace App\Actions\Project\ClubAccess;

use Telegram\Bot\Api;

use App\Actions\Core\BotSendMessage\BotSendMessage;
use App\Actions\Core\BotUser\BotUserSetEmail;
use App\Actions\Core\BotUser\BotUserSetListener;
use App\Actions\Core\DateEnd\DateEnd;
use App\Actions\Core\Max\MaxQuery;
use App\Actions\Core\Pay\PayCreateByPayGuest;
use App\Models\Core\BotUser;
use App\Models\Core\Pay;
use App\Models\Core\Product;
use App\Actions\Core\Telegram\TelegramQuery;

class BotListenerEmail
{
    public function handle(string $messenger, $webhook, $bot_user) {
        $botSendMessage = new BotSendMessage();
        $botUserSetEmail = new BotUserSetEmail();
        $botUserSetListener = new BotUserSetListener();
        $botWrongEmail = new BotWrongEmail();
        $dateEnd = new DateEnd();
        $maxQuery = new MaxQuery();
        $telegramQuery = new TelegramQuery();
        $payCreateByPayGuest = new PayCreateByPayGuest();

        //== Проверяем, ожидает ли юзер ввода почты
        if ($bot_user->listen_email_status == 1) {

            //== Если ожидает, то проверяем, является ли введенная пользователем строка почтой через стандартный определитель ТГ
            if (($messenger == 'telegram' && isset($webhook['message']['entities']) && $webhook['message']['entities'][0]['type']=='email') ||
                ($messenger == 'max' && (isset($webhook['message']['body']['text'])))) {

                if ($messenger == 'telegram') (string) $email = $webhook['message']['text'];
                if ($messenger == 'max') (string) $email = $webhook['message']['body']['text'];

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $botSendMessage->handle($bot_user, 'SYS_ENTERED_EMAIL_INCORRECT');
                    die();
                }

                if ($messenger == 'max') {

                    $bot_user_telegram = BotUser::with('bot')
                        ->where('email', $email)
                        ->whereNotNull('telegram_chat_id')
                        ->whereNull('max_user_id')
                        ->where('bot_id', $bot_user->bot_id)
                        ->first();

                    if ($bot_user_telegram) {

                        BotUser::where('id', $bot_user_telegram->id)->update(['verification_from_max' => $bot_user->max_user_id]);

                        $kb = [];
                        $btn = [["text" => 'Подтвердить', "callback_data" => 'connect_max_to_telegram_'.$bot_user->max_user_id]];
                        $kb[] = $btn;
                        $keyboard = ["inline_keyboard" => $kb];
                        $keyboard = json_encode($keyboard, true);

                        $A = [];
                        $A['text'] = 'Нажмите на кнопку, чтобы подтвердить подключение аккаунта в Max';
                        $A['reply_markup'] = $keyboard;
                        $A['parse_mode'] = 'HTML';
                        $A['chat_id'] = $bot_user_telegram->telegram_chat_id;
                        $A['protect_content'] = false;

                        $telegramQuery->handle($bot_user->bot, 'sendMessage', ['chat_id' => $bot_user_telegram->telegram_user_id, 'text' =>  'Нажмите на кнопку, чтобы подтвердить подключение аккаунта в Telegram']);

                        $botSendMessage->handle($bot_user, 'SYS_SEND_IN_MAX_BEFORE_VERIFICATION_FROM_MAX', 'max');
                        die();
                    }

                }

                if ($messenger == 'telegram') {

                    $bot_user_max = BotUser::with('bot')
                        ->where('email', $email)
                        ->whereNotNull('max_user_id')
                        ->whereNull('telegram_chat_id')
                        ->where('bot_id', $bot_user->bot_id)
                        ->first();

                    if ($bot_user_max) {

                        BotUser::where('id', $bot_user_max->id)->update(['verification_from_telegram' => $bot_user->telegram_chat_id]);

                        $kb = [];
                        $btn = [["text" => 'Подтвердить', "payload" => 'connect_telegram_to_max_'.$bot_user->telegram_chat_id, "type" => "callback"]];
                        $kb[] = $btn;

                        $A = [];
                        $A['attachments'] = [];
                        $A['attachments'][] = ["type" => "inline_keyboard", "payload" => ["buttons" => $kb]];
                        $A['text'] = 'Нажмите на кнопку, чтобы подтвердить подключение аккаунта в Telegram';
                        $A['format'] = 'html';
                        $A['chat_id'] = $bot_user_max->max_user_id;

                        $maxQuery->handle($bot_user->bot, 'POST', 'messages', $A, false, ['user_id' => $bot_user_max->max_user_id]);

                        $botSendMessage->handle($bot_user, 'SYS_SEND_IN_TELEGRAM_BEFORE_VERIFICATION_FROM_TELEGRAM', 'telegram');
                        die();
                    }

                }

                $other_bot_user = BotUser::where('email', $email)->whereNot('id', $bot_user->id)->where('bot_id', $bot_user->bot_id)->count();

                if ($other_bot_user > 0) {
                    $botSendMessage->handle($bot_user, 'SYS_OTHER_USER_WITH_ENTERED_EMAIL');
                    die();
                }

                //== Если юзер ввёл почту, то привязываем её ему к аккаунту
                $botUserSetEmail->handle($bot_user, $email);

                //== Проверяем гостевые платежи и перекачиваем
                $pay_guest_count = $payCreateByPayGuest->handle($bot_user, $email);

                if ($bot_user->listen_check_access_status == 1) {
                    $products = Product::select('id')->where('bot_id', $bot_user->bot_id)->pluck('id')->toArray();

                    if (count($products) > 0) {

                        $pays = Pay::where('bot_user_id', $bot_user->id)->whereIn('product_id', $products)->where('status', 1)->count();
                        if ($pays == 0) $pays = Pay::where('gift_bot_user_id', $bot_user->id)->whereIn('product_id', $products)->where('status', 1)->count();

                        if ($pays > 0) {
                            $date_end = $dateEnd->handle($bot_user, 'Y-m-d');

                            $bot_user = BotUser::find($bot_user->id);

                            if ($date_end > date('Y-m-d', time())) {
                                if ($pay_guest_count == 0) {
                                    $botSendMessage->handle($bot_user, 'SYS_SUCCESS_MESSAGE');
                                    $botUserSetListener->handle('sys_go_to_pay', 0, $bot_user->id);
                                }

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

                $bot_user = BotUser::find($bot_user->id);

                if ($bot_user->sys_go_to_pay_status == 1 && ($bot_user->date_end == NULL || $bot_user->date_end < date('Y-m-d', time()))) {
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
