<?php
namespace App\Actions\Core\Telegram;

use App\Models\Core\Bot;
use App\Models\Core\TelegramWebhook;

use App\Actions\Core\Pay\PaySuccessfulFromTelegramCallback;
use App\Actions\Core\Telegram\TelegramChatJoinRequest;
use App\Actions\Core\Telegram\TelegramAnswerPreCheckoutQuery;

class TelegramWebhookWrite
{
    public function handle($data, int $bot_id) {

        $paySuccessfulFromTelegramCallback = new PaySuccessfulFromTelegramCallback();
        $telegramChatJoinRequest = new TelegramChatJoinRequest();
        $telegramAnswerPreCheckoutQuery = new TelegramAnswerPreCheckoutQuery();

        $json = json_decode($data, true);

        $A = [];
        $A['bot_id'] = $bot_id;
        $A['callback'] = $data;

        if (isset($json['update_id'])) $A['update_id']=$json['update_id'];

        if (isset($json['business_message']['chat']['id'])) $A['business_message_chat_id']=$json['business_message']['chat']['id'];
        if (isset($json['business_message']['from']['id'])) $A['business_message_from_id']=$json['business_message']['from']['id'];
        if (isset($json['business_message']['message_id'])) $A['message_id']=$json['business_message']['message_id'];

        if (isset($json['channel_post']['chat'])) {
            if (isset($json['channel_post']['chat']['id'])) $A['channel_id'] = $json['channel_post']['chat']['id'];
            if (isset($json['channel_post']['chat']['type'])) $A['channel_type'] = $json['channel_post']['chat']['type'];
            if (isset($json['channel_post']['chat']['title'])) $A['channel_title'] = $json['channel_post']['chat']['title'];
            if (isset($json['channel_post']['chat']['username'])) $A['channel_username'] = $json['channel_post']['chat']['username'];
        }

        if (isset($json['channel_post']['date'])) $A['date'] = date('Y.m.d H:i:s', $json['channel_post']['date']);
        if (isset($json['channel_post']['text'])) $A['text'] = $json['channel_post']['text'];
        if (isset($json['channel_post']['caption'])) $A['caption'] = $json['channel_post']['caption'];

        $A['created_at']=now();
        $A['updated_at']=now();

        TelegramWebhook::upsert($A, ['update_id'], ['updated_at']);

        if (isset($json['business_message']['business_connection_id'])) {
            Bot::where('id', $bot_id)->update(['business_connection_id' => $json['business_message']['business_connection_id']]);
        }

        if (isset($json['chat_join_request'])) {
            return $telegramChatJoinRequest->handle($bot_id, $json);
        }

        if (isset($json['pre_checkout_query'])) {
            return $telegramAnswerPreCheckoutQuery->handle($bot_id, $json);
        }

        if (isset($json['message']['successful_payment'])) {
            return $paySuccessfulFromTelegramCallback->handle($bot_id, $json);
        }

        return $json;
    }
}
