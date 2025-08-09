<?php
namespace App\Actions\Core\Telegram;

use App\Actions\Core\BotSendMessage\BotSendMessage;
use App\Actions\Core\BotUser\BotUserGetFromTelegram;
use App\Actions\Core\Telegram\TelegramChatJoinRequestCreate;

use App\Models\Core\BotUser;

class TelegramChatJoinRequest
{
    public function handle(int $bot_id, $json) {
        $botSendMessage = new BotSendMessage();
        $botUserGetFromTelegram = new BotUserGetFromTelegram();
        $telegramChatJoinRequestCreate = new TelegramChatJoinRequestCreate();

        if (isset($json['chat_join_request']['chat']['id']) && isset($json['user_chat_id'])) {
            $bot_user = $botUserGetFromTelegram->handle($bot_id, $json['user_chat_idd']);

            if ($bot_user) {

                if (isset($bot_user->date_end)) {

                    if ($bot_user->date_end > date("Y-m-d H:i:s", time())) {
                        $botSendMessage->handle($bot_user, 'SYS_APPROVE_CHAT_JOIN_REQUEST');
                        $telegramChatJoinRequestCreate->handle($bot_user, 1);
                    } else {
                        $botSendMessage->handle($bot_user, 'SYS_DECLINE_CHAT_JOIN_REQUEST');
                        $telegramChatJoinRequestCreate->handle($bot_user, 0);
                    }

                } else {
                    $botSendMessage->handle($bot_user, 'SYS_DECLINE_CHAT_JOIN_REQUEST');
                    $telegramChatJoinRequestCreate->handle($bot_user, 0);
                }

            } else {
                $botSendMessage->handle($bot_user, 'SYS_DECLINE_CHAT_JOIN_REQUEST');
                $telegramChatJoinRequestCreate->handle($bot_user, 0);
            }
        }

    }
}
