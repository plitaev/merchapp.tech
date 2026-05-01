<?php
namespace App\Http\Controllers\Core;

use App\Actions\Core\Auto\BotUserSetBanScheduler;
use App\Actions\Core\Auto\BotUserSetBanSchedulerCreate;
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
use App\Models\Core\BotUserUnbanSchedule;
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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Public;


use App\Models\Core\Bot;

class DevTestTelegramController extends Controller
{
    public function devtesttelegram()
    {

        $botSupergroupsAll = new BotSupergroupsAll();
        $botUserInsertVariables = new BotUserInsertVariables();
        $telegramSendMessage = new TelegramSendMessage();
        $botUserGetByEmail = new BotUserGetByEmail();
        $botMessageGetByID = new BotMessageGetByID();
        $botUserUnban = new BotUserUnban();
        $botUserSetUnbanScheduler = new BotUserSetUnbanScheduler();
        $TelegramQuery = new TelegramQuery();
        $TelegramWebhookMake = new TelegramWebhookMake();

        $bot_message_id = 425;

        $bot_id = 1;

        $datetime = date('Y-m-d H:i:s', time());

        $bot = Bot::find($bot_id);

        $supergroup = TelegramSupergroup::find(3);

        $bot_message = $botMessageGetByID->handle($bot_message_id);
        $telegram = new Api($bot_message->bot->telegram_token);

        $bot_user = $botUserGetByEmail->handle($bot_id, auth()->user()->email);

        //=========================================================================================================================

        $webhook_address = $TelegramWebhookMake->handle(1, 'test_address');
        $status = $TelegramQuery->handle($bot, 'subscriptions', ['url' => $webhook_address, 'secret' => hash('sha256', env('APP_URL'))], false);

        //=========================================================================================================================

        // Получить информацию о текущем webhook
        echo 'Информация о текущем webhook:<br>';
        $getWebhookInfo = $TelegramQuery->handle($bot, 'getWebhookInfo', []);
        print_r($getWebhookInfo);
        echo '<br><br>';

        // Получить количество участников
        echo 'Количество участников:<br>';
        $getChatMemberCount = $TelegramQuery->handle($bot, 'getChatMemberCount', ['chat_id' => $supergroup->telegram_id]);
        print_r($getChatMemberCount);
        echo '<br><br>';

        //Отправить сообщение
        echo 'Отправить сообщение:<br>';
        $sendMessage = $TelegramQuery->handle($bot, 'sendMessage', [
            'chat_id' => $supergroup->telegram_id,
            'text' => 'test message text',
        ]);
        print_r($sendMessage);
        echo '<br><br>';
        $botUserSetUnbanScheduler->handle($bot_user, date('Y-m-d H:i:s'));

        // Получить информацию о конкретном участнике
        echo 'Информация о конкретном участнике:<br>';

        $getChatMember = $TelegramQuery->handle($bot, 'getChatMember', [
            'chat_id' => $supergroup->telegram_id,
            'user_id' => $bot_user->telegram_chat_id,
        ]);
        print_r($getChatMember);
        echo '<br><br>';

        // Забанить пользователя
        echo 'Забанить пользователя:<br>';
        $banChatMember = $TelegramQuery->handle($bot, 'banChatMember', [
            'chat_id' => $supergroup->telegram_id,
            'user_id' => 8408592037,
            // 'until_date' => time() + 3600,
        ]);
        print_r($banChatMember);
        echo '<br><br>';

//        // Разбанить пользователя
        echo 'Разбанить пользователя:<br>';
        $unbanChatMember = $TelegramQuery->handle($bot, 'unbanChatMember', [
            'chat_id' => $supergroup->telegram_id,
            'user_id' => 8408592037,
        ]);
        print_r($unbanChatMember);
        echo '<br><br>';

        //Отправить сообщение c картинкой
        echo 'Отправить сообщение c картинкой:<br>';
        $imagePath = 'https://kartinki.pibig.info/uploads/posts/2023-12/1701746323_kartinki-pibig-info-p-samie-krasivie-kartinki-na-telefon-vkontak-1.jpg';

        //$imagePath = 'https://dev.merchapp.bot/public/1.jpeg';

        $sendMessageImage = $TelegramQuery->handle($bot, 'sendPhoto', [
            'chat_id' => $supergroup->telegram_id,
            'text' => 'test message image',
            'photo' => $imagePath, // или URL изображения
        ]);
        print_r($sendMessageImage);
        echo '<br><br>';
    }
}


