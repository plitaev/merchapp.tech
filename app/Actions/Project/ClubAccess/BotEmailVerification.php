<?php
namespace App\Actions\Project\ClubAccess;

use App\Actions\Core\BotSendMessage\BotSendMessage;
use App\Actions\Core\DateEnd\DateEnd;
use App\Actions\Core\Pay\PayCreateByPayGuest;
use App\Actions\Project\ClubAccess\BotMainMenuMessage;
use App\Actions\Project\ClubAccess\BotWrongEmail;

use App\Models\Core\BotUser;
use App\Models\Core\Pay;
use App\Models\Core\Product;

class BotEmailVerification
{
    public function handle($telegram, $bot_user, $webhook) {

        $botMainMenuMessage = new BotMainMenuMessage();
        $botSendMessage = new BotSendMessage();
        $botWrongEmail = new BotWrongEmail();
        $dateEnd = new DateEnd();
        $payCreateByPayGuest = new PayCreateByPayGuest();

        if (isset($webhook['callback_query']['message']['message_id'])) {
            $telegram->answerCallbackQuery(['callback_query_id' => $webhook['callback_query']['id']]);

            //== Вставка проверки доступа

            if ($bot_user->listen_check_access_status == 1) {
                $products = Product::select('id')->where('bot_id', $bot_user->bot_id)->pluck('id')->toArray();

                if (count($products) > 0) {

                    $pays = Pay::where('bot_user_id', $bot_user->id)->whereIn('product_id', $products)->where('status', 1)->count();
                    if ($pays == 0) $pays = Pay::where('gift_bot_user_id', $bot_user->id)->whereIn('product_id', $products)->where('status', 1)->count();

                    //== Проверяем гостевые платежи и перекачиваем
                    $pay_guest_count = $payCreateByPayGuest->handle($bot_user, $webhook['message']['text']);

                    if ($pays > 0) {
                        $date_end = $dateEnd->handle($bot_user, 'Y-m-d');

                        $bot_user = BotUser::find($bot_user->id);

                        if ($date_end > date('Y-m-d', time())) {
                            if ($pay_guest_count == 0) $botSendMessage->handle($bot_user, 'SYS_SUCCESS_MESSAGE');

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

            //== Конец вставки проверки доступа

            if ($bot_user->sys_go_to_pay_status == 1) {
                return $botSendMessage->handle($bot_user, 'SYS_PAY_IN_BOT');
                die();
            } else {
                $botMainMenuMessage->handle($bot_user);
            }

        }
    }
}
