<?php
namespace App\Actions\Core\Telegram;
use Telegram\Bot\Api;

use App\Actions\Core\BotSendMessage\BotSendMessage;

use App\Models\Core\TelegramSupergroup;
use App\Models\Core\TelegramChatJoinRequestLog;

class TelegramChatJoinRequestCreate
{
    public function handle($bot_user, $json, int $status) {
        $botSendMessage = new BotSendMessage();

        $supergroup = TelegramSupergroup::where('telegram_id', $json['chat_join_request']['chat']['id'])->first();
        $telegram = new Api($bot_user->bot->telegram_token);

        if ($status === 1 && $supergroup && $supergroup->decline_chat_join_request == 0) {
            $botSendMessage->handle($bot_user, 'SYS_APPROVE_CHAT_JOIN_REQUEST');

            try {
                $result = $telegram->approveChatJoinRequest(['chat_id' => $json['chat_join_request']['chat']['id'], 'user_id' => $json['chat_join_request']['user_chat_id']]);
            } catch (\Exception $exception) {}

        } else {
            $botSendMessage->handle($bot_user, 'SYS_DECLINE_CHAT_JOIN_REQUEST');

            try {
                $result = $telegram->declineChatJoinRequest(['chat_id' => $json['chat_join_request']['chat']['id'], 'user_id' => $json['chat_join_request']['user_chat_id']]);
            } catch (\Exception $exception) {}
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
