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

        if (isset($json['chat_join_request']['chat']['id']) && isset($json['chat_join_request']['user_chat_id'])) {

            $first_name = (isset($webhook['chat_join_request']['invite_link']['creator']['first_name'])?$webhook['chat_join_request']['invite_link']['creator']['first_name']:'none');
            $last_name = (isset($webhook['chat_join_request']['invite_link']['creator']['last_name'])?$webhook['chat_join_request']['invite_link']['creator']['last_name']:'none');
            $username = (isset($webhook['chat_join_request']['invite_link']['creator']['username'])?$webhook['chat_join_request']['invite_link']['creator']['username']:'none');
            $language_code = (isset($webhook['chat_join_request']['invite_link']['creator']['language_code'])?$webhook['chat_join_request']['invite_link']['creator']['language_code']:'no');

            \App\Models\Core\BotUser::updateOrCreate(
                ['telegram_chat_id' => $json['chat_join_request']['user_chat_id'], 'bot_id' => $bot_id],
                ['first_name' => $first_name, 'last_name' => $last_name, 'username' => $username, 'language_code' => $language_code]
            );

            $bot_user = $botUserGetFromTelegram->handle($bot_id, $json['chat_join_request']['user_chat_id']);

            if ($bot_user) {

                if (isset($bot_user->date_end)) {

                    if ($bot_user->date_end > date("Y-m-d H:i:s", time())) {
                        $botSendMessage->handle($bot_user, 'SYS_APPROVE_CHAT_JOIN_REQUEST');
                        $telegramChatJoinRequestCreate->handle($bot_id, $json, 1);
                    } else {
                        $botSendMessage->handle($bot_user, 'SYS_DECLINE_CHAT_JOIN_REQUEST');
                        $telegramChatJoinRequestCreate->handle($bot_id, $json, 0);
                    }

                } else {
                    $botSendMessage->handle($bot_user, 'SYS_DECLINE_CHAT_JOIN_REQUEST');
                    $telegramChatJoinRequestCreate->handle($bot_id, $json, 0);
                }

            } else {
                $botSendMessage->handle($bot_user, 'SYS_DECLINE_CHAT_JOIN_REQUEST');
                $telegramChatJoinRequestCreate->handle($bot_id, $json, 0);
            }
        }

    }
}
