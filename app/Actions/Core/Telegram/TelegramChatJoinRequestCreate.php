<?php
namespace App\Actions\Core\Telegram;

use App\Actions\Core\BotSendMessage\BotSendMessage;
use App\Actions\Core\Telegram\TelegramQuery;

use App\Models\Core\TelegramChatJoinRequestLog;
use App\Models\Core\TelegramSupergroup;

class TelegramChatJoinRequestCreate
{
    public function handle($bot_user, $json, int $status) {
        $botSendMessage = new BotSendMessage();
        $telegramQuery = new TelegramQuery();

        $supergroup = TelegramSupergroup::where('telegram_id', $json['chat_join_request']['chat']['id'])->first();

        if ($status === 1 && $supergroup && $supergroup->decline_chat_join_request == 0) {
            $botSendMessage->handle($bot_user, 'SYS_APPROVE_CHAT_JOIN_REQUEST');

            try {
                $result = $telegramQuery->handle($bot_user->bot, 'approveChatJoinRequest', ['chat_id' => $json['chat_join_request']['chat']['id'], 'user_id' => $json['chat_join_request']['user_chat_id']]);
            } catch (\Exception $exception) {
                $result = '{"merchApp Error": "approveChatJoinRequest"}';
            }

        } else {
            $botSendMessage->handle($bot_user, 'SYS_DECLINE_CHAT_JOIN_REQUEST');

            try {
                $result = $telegramQuery->handle($bot_user->bot, 'declineChatJoinRequest', ['chat_id' => $json['chat_join_request']['chat']['id'], 'user_id' => $json['chat_join_request']['user_chat_id']]);
            } catch (\Exception $exception) {
                $result = '{"merchApp Error": "declineChatJoinRequest"}';
            }
        }

        TelegramChatJoinRequestLog::create([
            'chat_id' => $json['chat_join_request']['chat']['id'],
            'user_id' => $json['chat_join_request']['user_chat_id'],
            'invite_link' => $json['chat_join_request']['invite_link']['invite_link'],
            'status' => $status,
            'telegram_data' => json_encode($result, true)
        ]);
    }
}
