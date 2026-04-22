<?php
namespace App\Http\Controllers\Core;

use App\Actions\Core\BotMessage\BotMessageGetByID;
use App\Actions\Core\BotSupergroup\BotSupergroupsAll;
use App\Actions\Core\BotUser\BotUserGetByEmail;
use App\Actions\Core\BotUser\BotUserInsertVariables;
use App\Actions\Core\BotUser\BotUserSetUnbanScheduler;
use App\Actions\Core\BotUser\BotUserUnban;
use App\Actions\Core\BotUserPrice\BotUserPriceGet;
use App\Actions\Core\PaySystemCallback\PaySystemCallbackCreate;
use App\Actions\Core\Product\ProductListByBot;
use App\Actions\Core\Telegram\TelegramQuery;
use App\Actions\Core\Telegram\TelegramSendMessage;
use App\Actions\Core\Telegram\TelegramWebhookInfo;
use App\Actions\Core\Telegram\TelegramWebhookMake;
use App\Models\Core\BotMessage;
use App\Models\Core\BotMessageButton;
use App\Models\Core\GetcourseWebhook;
use App\Models\Core\TelegramSendMessageErrorLog;
use App\Models\Core\TelegramSendMessageLog;
use App\Models\Core\TelegramSendMessageSchedule;
use App\Models\Core\TelegramSupergroup;
use Carbon\Carbon;

use App\Http\Controllers\Controller;

use App\Models\Core\BotUser;
use App\Models\Core\BotUserBanSchedule;
use App\Models\Core\BotUserPrice;
use App\Models\Core\GetcourseWebhookTicket;
use App\Models\Core\Product;
use Illuminate\Support\Facades\DB;
use Telegram\Bot\Api;

use App\Models\Core\Pay;
use App\Models\Core\PayGuest;
use App\Models\Core\PaySystemCallback;

use App\Actions\Core\DateEnd\DateEndNew;
use App\Actions\Core\DateEnd\DateEnd;
use App\Models\Core\TelegramBanScheduleLogs;

use App\Models\Core\Bot;

class DevTestTelegramController extends Controller
{
    public function devtesttelegram() {

        $botSupergroupsAll = new BotSupergroupsAll();
        $botUserInsertVariables = new BotUserInsertVariables();
        $telegramSendMessage = new TelegramSendMessage();
        $botUserGetByEmail = new BotUserGetByEmail();
        $botMessageGetByID = new BotMessageGetByID();
        $botUserUnban = new BotUserUnban();
        $botUserSetUnbanScheduler = new BotUserSetUnbanScheduler();

        $bot_message_id = 425;

        $bot_id = 1;

        $bot_message = $botMessageGetByID->handle($bot_message_id);
        $telegram = new Api($bot_message->bot->telegram_token);

        $bot_user = $botUserGetByEmail->handle($bot_id, auth()->user()->email);

        $botUserInsertVariables = new BotUserInsertVariables();

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
                        $url = env("APP_URL") . "/go/" . base64_encode($button->id) . "/" . base64_encode($bot_user->id);
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


                    $btn = [['url' => env("APP_URL") . "/pay/create/" . $button->pay_system->alias . "/" . $bot_user->id . "/" . $button->product_id, "text" => $button_name]];
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
                $A['caption'] = $text;
            } else {
                $A['text'] = $text;
            }
            $A['format'] = 'html';
            $A['attachments'] = [];
            $A['chat_id'] = 5460330541;
            $A['reply_markup'] = $keyboard;
            $A['parse_mode'] = 'HTML';
            $A['protect_content'] = false;
            if (count($kb) > 0) $A['reply_markup'] = $keyboard;
            if (isset($bot_message->bot->business_connection_id)) $A['business_connection_id'] = $bot_message->bot->business_connection_id;

            (string)$entities = NULL;


            //=========================================================================================================================

            if ($bot_message->image && $send_status == 0) {
                //$A['photo'] = \Telegram\Bot\FileUpload\InputFile::create(env('APP_URL').'/content/'.$bot_message->image);
                $A['photo'] = env('APP_URL').'/content/'.$bot_message->image;

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

            if ($bot_message->custom_file && $send_status == 0) {

                $Aext = explode('.', $bot_message->custom_file);
                $file_ext = $Aext[count($Aext)-1];

                $filename = (isset($bot_message->custom_file_name)?$bot_message->custom_file_name:'Файл');

                $A['document'] = \Telegram\Bot\FileUpload\InputFile::create(env('APP_URL').'/content/'.$bot_message->custom_file, $filename.'.'.$file_ext);

                try {
                    $message = $telegram->sendDocument($A);
                    $entities = $message->caption_entities;
                } catch (\Exception $exception) {
                    TelegramSendMessageErrorLog::create(['chat_id' => $bot_user->telegram_chat_id, 'bot_message_id' => $bot_message_id, 'text' => $exception]);
                }

                $send_status = 1;
            }

            //=========================================================================================================================
//
//            if (!$bot_message->image && !$bot_message->video && !$bot_message->audio && !$bot_message->custom_file && $send_status == 0) {
//                try {
//                    $message = $telegram->sendMessage($A);
//                    $entities = $message->entities;
//                } catch (\Exception $exception) {
//                    TelegramSendMessageErrorLog::create(['chat_id' => $bot_user->telegram_chat_id, 'bot_message_id' => $bot_message_id, 'text' => $exception]);
//                }
//
//                $send_status = 1;
//            }


            //=========================================================================================================================

//            if (isset($message)) {
//
//                TelegramSendMessageLog::create(
//                    [
//                        'chat_id' => $bot_user->telegram_chat_id,
//                        'bot_message_id' => $bot_message_id,
//                        'text' => $bot_message->text,
//                        'keyboard' => $keyboard,
//                        'telegram_message_id' => $message->message_id,
//                        'telegram_message_data' => json_encode($message, true),
//                        'telegram_entities' => $entities
//                    ]
//                );
//
//
//                return $message;
//            }

            //=========================================================================================================================

            $TelegramQuery = new TelegramQuery();
            $TelegramWebhookMake = new TelegramWebhookMake();


            $bot = Bot::find($bot_id);

            $webhook_address = $TelegramWebhookMake->handle(1, 'test_address');
            $status = $TelegramQuery->handle($bot, 'POST', 'subscriptions', ['url' => $webhook_address ,'secret' => hash('sha256', env('APP_URL'))], false);

            //=========================================================================================================================

            $telegram = new Api($bot_user->bot->telegram_token);

            $supergroup = TelegramSupergroup::find(3);

//            $banChatMember = $telegram->banChatMember(['chat_id' => $supergroup->telegram_id, 'user_id' => $bot_user->telegram_chat_id]);
//            $status_banChatMember = $TelegramQuery->handle($bot, 'POST', 'supergroups', ['url' => $banChatMember ,'secret' => hash('sha256', env('APP_URL'))], false);
//
//            $unbanChatMember = $telegram->unbanChatMember(['chat_id' => $supergroup->telegram_id, 'user_id' => $bot_user->telegram_chat_id, 'only_if_banned' => true]);
//            $status_unbanChatMember = $TelegramQuery->handle($bot, 'POST', 'supergroups', ['url' => $unbanChatMember ,'secret' => hash('sha256', env('APP_URL'))], false);
//
//            $getChat = $telegram->getChat(['chat_id' => $bot_user->telegram_chat_id]);
//            $status_getChat = $TelegramQuery->handle($bot, 'POST', 'supergroups', ['url' => $getChat ,'secret' => hash('sha256', env('APP_URL'))], false);



//
//            if ($bot_user) {
//                return $telegramSendMessage->handle($bot_user, $bot_message_id);
//            }

            //=========================================================================================================================
        }

    }
}

