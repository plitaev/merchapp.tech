<?php

namespace App\Actions\Core\Max;

use App\Actions\Core\BotSupergroup\BotSupergroupsAll;
use App\Actions\Core\BotSendMessage\BotSendMessage;
use App\Actions\Core\BotUser\BotUserInsertVariables;
use App\Actions\Core\BotUser\BotUserSetUnbanScheduler;
use App\Actions\Core\BotUser\BotUserUnban;
use App\Actions\Core\Max\MaxQuery;
use App\Actions\Core\BotUserPrice\BotUserPriceGet;
use App\Actions\Core\Product\ProductListByBot;

use App\Models\Core\BotMessage;
use App\Models\Core\BotMessageButton;
use App\Models\Core\MaxSendMessageLog;
use App\Models\Core\TelegramSupergroup;

class MaxSendMessage
{
    public function handle($bot_user, int $bot_message_id, string $bot_message_appointment = '') {
        $botSendMessage = new BotSendMessage();
        $botSupergroupsAll = new BotSupergroupsAll();
        $botUserInsertVariables = new BotUserInsertVariables();
        $botUserSetUnbanScheduler = new BotUserSetUnbanScheduler();
        $botUserUnban = new BotUserUnban();
        $maxQuery = new MaxQuery();
        $productListByBot = new ProductListByBot();

        (int) $send_status = 0;

        $bot_message = BotMessage::with('bot')->find($bot_message_id);

        $text = $bot_message->text;
        $text = urldecode($text);
        $text = $botUserInsertVariables->handle($bot_user, $text);

        if ($bot_message) {

            $kb = [];

            $buttons = BotMessageButton::with('bot_message_button_callbacks')->with('product')->where('bot_message_id', $bot_message_id)->orderBy('pos')->get();
            foreach ($buttons as $button) {

                if ($button->url) {

                    if ($button->tracking == 1) {
                        $url = env("APP_URL")."/go/".base64_encode($button->id)."/".base64_encode($bot_user->id);
                    } else {
                        $url = $button->url;
                    }

                    $btn = [["text" => $button->name, "url" => $url, "type" => "link"]];
                }

                if ($button->callback) {
                    $btn = [["text" => $button->name, "payload" => $button->callback, "type" => "callback"]];
                }

                if ($button->bot_message_button_callbacks) {
                    $btn = [["text" => $button->name, "payload" => $button->bot_message_button_callbacks->system_name, "type" => "callback"]];
                }

                if ($button->bot_message_button_type_id == 4) {
                    $button_name = $button->name;

                    if (stripos(strtolower($button_name), 'VAR_PRODUCT_PRICE')) {
                        $botUserPriceGet = new BotUserPriceGet();
                        $prices = $botUserPriceGet->handle($bot_user, true);

                        if (isset($prices[$button->product_id])) {
                            $button_name = str_replace('VAR_PRODUCT_PRICE', $prices[$button->product_id], $button_name);
                        }
                    }


                    $btn = [['url' => env("APP_URL")."/pay/create/".$button->pay_system->alias."/".$bot_user->id."/".$button->product_id, "text" => $button_name, "type" => "link"]];
                }

                $kb[] = $btn;
            }

            if ($bot_message_appointment == 'SYS_SUCCESS_MESSAGE_MAX') {

                $res = TelegramSupergroup::where('bot_id', $bot_user->bot_id)->get();
                foreach ($res as $data) {
                    $A = [];
                    $A['user_ids'] = [$bot_user->max_user_id];
                    return $maxQuery->handle($bot_user->bot, 'POST', 'chats/'.$data->max_user_id.'/members', $A, false, ['user_id' => $bot_user->max_user_id]);

                    $add_result = json_decode($add_result, true);

                    if (isset($add_result['failed_user_details'][0]['error_code']) && $add_result['failed_user_details'][0]['error_code'] == 'add.participant.privacy') {
                        $botSendMessage->handle($bot_user, 'SYS_SUCCESS_MESSAGE_MAX_NEED_PRIVACY_CHANGE', 'max');
                        die();
                    }
                }

            }

            $A = [];
            $A['text'] = $text;
            $A['format'] = 'html';
            $A['attachments'] = [];
            if (count($kb) > 0) $A['attachments'][] = ["type" => "inline_keyboard", "payload" => ["buttons" => $kb]];

            //=========================================================================================================================

            if ($bot_message->image) $A['attachments'][] = ["type" => "image", "payload" => ["url" => env('APP_URL').'/content/'.$bot_message->image]];

            //=========================================================================================================================

            if ($bot_message->video) $A['attachments'][] = ["type" => "video", "payload" => ["token" => $bot_message->video_max_id]];

            //=========================================================================================================================

            if ($bot_message->audio) $A['attachments'][] = ["type" => "audio", "payload" => ["token" => $bot_message->audio_max_id]];

            //=========================================================================================================================

            if ($bot_message->custom_file) $A['attachments'][] = ["type" => "file", "payload" => ["token" => $bot_message->custom_file_max_id]];

            //=========================================================================================================================

            try {
                $message = $maxQuery->handle($bot_user->bot, 'POST', 'messages', $A, false, ['user_id' => $bot_user->max_user_id]);
            } catch (\Exception $exception) {}

            //=========================================================================================================================

            if (isset($message)) {

                MaxSendMessageLog::create(
                    [
                        'max_user_id' => $bot_user->max_user_id,
                        'bot_message_id' => $bot_message_id,
                        'text' => $bot_message->text,
                        'keyboard' => json_encode($A['attachments']),
                        'max_responce_data' => $message
                    ]
                );


                //== Разбаниваем, если идет сообщение об успехе, и ставим в UnbanScheduler

                if ($bot_message_appointment == 'SYS_SUCCESS_MESSAGE_MAX') {
                    //$supergroups = $botSupergroupsAll->handle();
                    //$botUserUnban->handle($bot_user, $supergroups, $telegram);
                    //$botUserSetUnbanScheduler->handle($bot_user, date('Y-m-d H:i:s'));
                }

                return $message;
            }
        }
    }
}
