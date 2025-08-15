<?php
namespace App\Actions\Core\Telegram;
use Telegram\Bot\Api;

use App\Models\Core\Product;

use App\Actions\Core\Telegram\TelegramInviteLink;
use App\Actions\Core\Product\ProductListByBot;

use App\Models\Core\BotMessage;
use App\Models\Core\BotMessageButton;
use App\Models\Core\TelegramSendMessageErrorLog;
use App\Models\Core\TelegramSendMessageLog;

class TelegramSendMessage
{
    public function handle($bot_user, int $bot_message_id, string $bot_message_appointment = '') {

        $productListByBot = new ProductListByBot();
        $telegramInviteLink = new TelegramInviteLink();

        (int) $send_status = 0;

        $bot_message = BotMessage::with('bot:id,telegram_token,business_connection_id')->find($bot_message_id);

        $telegram = new Api($bot_message->bot->telegram_token);

        $text = $bot_message->text;
        $text = urldecode($text);

        //== Замены переменных

        //== Адрес почты

        if (stripos(strtolower($text), 'VAR_USER_EMAIL')) $text = str_replace('VAR_USER_EMAIL', $bot_user->email, $text);

        if (stripos(strtolower($text), 'VAR_USER_DATE_END')) {
            $date_end = date('d.m.Y', strtotime($bot_user->date_end));
            if ($date_end == '01.01.1970') $date_end = '';
            $text = str_replace('VAR_USER_DATE_END', $date_end, $text);
        }

        //== Конец замены переменных

        if ($bot_message) {

            $kb = [];

            if ($bot_message_appointment == 'SYS_PAY_IN_BOT') {

                $products = $productListByBot->handle($bot_user->bot_id);

                foreach ($products as $product) {
                    $btn = [["text" => $product->name." - ".$product->price." руб.", "web_app" => ['url' => env("APP_URL")."/pay/create/yookassa/".$product->id]]];
                    $kb[] = $btn;
                }

            } else {

                $buttons = BotMessageButton::with('bot_message_button_callbacks')->where('bot_message_id', $bot_message_id)->orderBy('pos')->get();
                foreach ($buttons as $button) {

                    if ($button->url) {

                        if ($button->url == "VAR_INVITE_LINK") {

                            $url = $telegramInviteLink->handle($bot_user, $telegram);

                        } else {

                            if ($button->tracking == 1) {
                                $url = env("APP_URL")."/go/".base64_encode($button->id)."/".base64_encode($bot_user->id);
                            } else {
                                $url = $button->url;
                            }

                        }

                        $btn = [["text" => $button->name, "url" => $url]];
                    }

                    if ($button->callback) {
                        $btn = [["text" => $button->name, "callback_data" => $button->callback]];
                    }

                    if ($button->bot_message_button_callbacks) {
                        $btn = [["text" => $button->name, "callback_data" => $button->bot_message_button_callbacks->system_name]];
                    }

                    $kb[] = $btn;
                }

            }

            if (count($kb) > 0) {
                $keyboard = ["inline_keyboard" => $kb];
                $keyboard = json_encode($keyboard, true);
            } else {
                $keyboard = NULL;
            }

            $A = [];

            if ($bot_message->image || $bot_message->video || $bot_message->audio) {
                $A['caption'] = $text;
            } else {
                $A['text'] = $text;
            }

            $A['chat_id'] = $bot_user->telegram_chat_id;
            $A['reply_markup'] = $keyboard;
            $A['parse_mode'] = 'HTML';
            $A['protect_content'] = false;
            if (count($kb) > 0) $A['reply_markup'] = $keyboard;
            if (isset($bot_message->bot->business_connection_id)) $A['business_connection_id'] = $bot_message->bot->business_connection_id;

            (string) $entities = NULL;

            //=========================================================================================================================

            if ($bot_message->image && $send_status == 0) {
                $A['photo'] = \Telegram\Bot\FileUpload\InputFile::create(env('APP_URL').'/content/'.$bot_message->image);

                try {
                    $message = $telegram->sendPhoto($A);
                    $entities = $message->caption_entities;
                } catch (\Exception $exception) {
                    TelegramSendMessageErrorLog::create(['chat_id' => $bot_user->telegram_chat_id, 'bot_message_id' => $bot_message_id, 'text' => $exception]);
                }

                $send_status = 1;
            }

            //=========================================================================================================================

            if ($bot_message->video && $send_status == 0) {
                $A['video'] = \Telegram\Bot\FileUpload\InputFile::create(env('APP_URL').'/content/'.$bot_message->video);

                try {
                    $message = $telegram->sendVideo($A);
                    $entities = $message->caption_entities;
                } catch (\Exception $exception) {
                    TelegramSendMessageErrorLog::create(['chat_id' => $bot_user->telegram_chat_id, 'bot_message_id' => $bot_message_id, 'text' => $exception]);
                }

                $send_status = 1;
            }

            //=========================================================================================================================

            if ($bot_message->audio && $send_status == 0) {
                $A['audio'] = \Telegram\Bot\FileUpload\InputFile::create(env('APP_URL').'/content/'.$bot_message->audio);

                try {
                    $message = $telegram->sendAudio($A);
                    $entities = $message->caption_entities;
                } catch (\Exception $exception) {
                    TelegramSendMessageErrorLog::create(['chat_id' => $bot_user->telegram_chat_id, 'bot_message_id' => $bot_message_id, 'text' => $exception]);
                }

                $send_status = 1;
            }

            //=========================================================================================================================

            if (!$bot_message->image && !$bot_message->video && !$bot_message->audio && $send_status == 0) {
                try {
                    $message = $telegram->sendMessage($A);
                    $entities = $message->entities;
                } catch (\Exception $exception) {
                    TelegramSendMessageErrorLog::create(['chat_id' => $bot_user->telegram_chat_id, 'bot_message_id' => $bot_message_id, 'text' => $exception]);
                }

                $send_status = 1;
            }

            //=========================================================================================================================

            if (isset($message)) {

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

                return $message;
            }

        }

    }
}
