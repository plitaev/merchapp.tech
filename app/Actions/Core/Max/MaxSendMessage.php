<?php

namespace App\Actions\Core\Max;

use App\Actions\Core\BotSupergroup\BotSupergroupsAll;
use App\Actions\Core\BotUser\BotUserInsertVariables;
use App\Actions\Core\BotUser\BotUserSetUnbanScheduler;
use App\Actions\Core\BotUser\BotUserUnban;
use App\Actions\Core\Max\MaxQuery;
use App\Actions\Core\BotUserPrice\BotUserPriceGet;
use App\Actions\Core\Product\ProductListByBot;

use App\Models\Core\BotMessage;
use App\Models\Core\BotMessageButton;
use App\Models\Core\TelegramSendMessageErrorLog;
use App\Models\Core\TelegramSendMessageLog;

class MaxSendMessage
{
    public function handle($bot_user, int $bot_message_id, string $bot_message_appointment = '') {

        $botSupergroupsAll = new BotSupergroupsAll();
        $botUserInsertVariables = new BotUserInsertVariables();
        $botUserSetUnbanScheduler = new BotUserSetUnbanScheduler();
        $botUserUnban = new BotUserUnban();
        $maxQuery = new MaxQuery();
        $productListByBot = new ProductListByBot();

        (int) $send_status = 0;

        $bot_message = BotMessage::with('bot:id,telegram_token,business_connection_id')->find($bot_message_id);

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

                    $btn = [["text" => $button->name, "url" => $url]];
                }

                if ($button->callback) {
                    $btn = [["text" => $button->name, "callback_data" => $button->callback]];
                }

                if ($button->bot_message_button_callbacks) {
                    $btn = [["text" => $button->name, "callback_data" => $button->bot_message_button_callbacks->system_name]];
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


                    $btn = [['url' => env("APP_URL")."/pay/create/".$button->pay_system->alias."/".$bot_user->id."/".$button->product_id, "text" => $button_name]];
                }

                $kb[] = $btn;
            }


            if (count($kb) > 0) {
                $keyboard = ["inline_keyboard" => $kb];
                $keyboard = json_encode($keyboard, true);
            } else {
                $keyboard = NULL;
            }

            $A = [];

            if ($bot_message->image || $bot_message->video || $bot_message->audio || $bot_message->custom_file) {
                $A['text'] = $text;
            } else {
                $A['text'] = $text;
            }

            $A['reply_markup'] = $keyboard;
            $A['parse_mode'] = 'HTML';
            if (count($kb) > 0) $A['reply_markup'] = $keyboard;

            //=========================================================================================================================

            if ($bot_message->image && $send_status == 0) {
                $A['photo'] = \Telegram\Bot\FileUpload\InputFile::create(env('APP_URL').'/content/'.$bot_message->image);

                try {
                    //$message = $telegram->sendPhoto($A);
                } catch (\Exception $exception) {
                    TelegramSendMessageErrorLog::create(['chat_id' => $bot_user->telegram_chat_id, 'bot_message_id' => $bot_message_id, 'text' => $exception]);
                }

                $send_status = 1;
            }

            //=========================================================================================================================

            if ($bot_message->video && $send_status == 0) {
                $A['video'] = \Telegram\Bot\FileUpload\InputFile::create(env('APP_URL').'/content/'.$bot_message->video);

                try {
                    //$message = $telegram->sendVideo($A);
                } catch (\Exception $exception) {
                    TelegramSendMessageErrorLog::create(['chat_id' => $bot_user->telegram_chat_id, 'bot_message_id' => $bot_message_id, 'text' => $exception]);
                }

                $send_status = 1;
            }

            //=========================================================================================================================

            if ($bot_message->audio && $send_status == 0) {
                $A['audio'] = \Telegram\Bot\FileUpload\InputFile::create(env('APP_URL').'/content/'.$bot_message->audio);

                try {
                    //$message = $telegram->sendAudio($A);
                } catch (\Exception $exception) {
                    TelegramSendMessageErrorLog::create(['chat_id' => $bot_user->telegram_chat_id, 'bot_message_id' => $bot_message_id, 'text' => $exception]);
                }

                $send_status = 1;
            }

            //=========================================================================================================================

            if ($bot_message->custom_file && $send_status == 0) {

                $Aext = explode('.', $bot_message->custom_file);
                $file_ext = $Aext[count($Aext)-1];

                $filename = (isset($bot_message->custom_file_name)?$bot_message->custom_file_name:'Файл');

                $A['document'] = \Telegram\Bot\FileUpload\InputFile::create(env('APP_URL').'/content/'.$bot_message->custom_file, $filename.'.'.$file_ext);

                try {
                    //$message = $telegram->sendDocument($A);
                } catch (\Exception $exception) {
                    TelegramSendMessageErrorLog::create(['chat_id' => $bot_user->telegram_chat_id, 'bot_message_id' => $bot_message_id, 'text' => $exception]);
                }

                $send_status = 1;
            }

            //=========================================================================================================================

            if (!$bot_message->image && !$bot_message->video && !$bot_message->audio && !$bot_message->custom_file && $send_status == 0) {
                try {
                    $maxQuery->handle($bot_user->bot, 'POST', 'messages', ['user_id' => $bot_user->max_user_id]);
                } catch (\Exception $exception) {
                }

                $send_status = 1;
            }

            //=========================================================================================================================

            if (isset($message)) {
                /*
                TelegramSendMessageLog::create(
                    [
                        'chat_id' => $bot_user->telegram_chat_id,
                        'bot_message_id' => $bot_message_id,
                        'text' => $bot_message->text,
                        'keyboard' => $keyboard,
                        'telegram_message_id' => $message->message_id,
                        'telegram_message_data' => json_encode($message, true),
                        'telegram_entities' => $entities
                    ]
                );
                */

                //== Разбаниваем, если идет сообщение об успехе, и ставим в UnbanScheduler

                if ($bot_message_appointment == 'SYS_SUCCESS_MESSAGE') {
                    $supergroups = $botSupergroupsAll->handle();
                    //$botUserUnban->handle($bot_user, $supergroups, $telegram);
                    $botUserSetUnbanScheduler->handle($bot_user, date('Y-m-d H:i:s'));
                }

                return $message;
            }

        }

    }
}
