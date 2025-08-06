<?php
namespace App\Actions\Project\ClubAccess;

use App\Actions\Core\BotSendMessage\BotSendMessage;
use App\Models\Core\BotUser;

class BotHandName
{
    public function handle($bot_user, $webhook) {
        $botSendMessage = new BotSendMessage();

        //== Проверяем, есть ли у юзера вручную введенное имя
        if (!isset($bot_user->hand_name)) {

            //== Проверяем, включен ли listener на имя
            if ($bot_user->listen_handname_status == 1) {

                //== Если включен, то читаем введенный текст, и записываем его в имя
                if (isset($webhook['message']['text'])) {
                    BotUser::where('id', $bot_user->id)->update(['hand_name' => $webhook['message']['text'], 'listen_handname_status' => 0, 'listen_handname_status_timestamp' => now()]);
                }

            } else {
                //== Если не получал, то отправляем сообщение с запросом имени и ставим listener
                $botSendMessage->handle($bot_user, 'SYS_REQUEST_NAME');
                die();
            }

        }
    }
}
