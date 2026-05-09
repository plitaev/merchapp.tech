<?php
namespace App\Http\Controllers\Core;

use App\Actions\Core\BotSupergroup\BotSupergroupsAll;
use App\Actions\Core\BotUser\BotUserInsertVariables;
use App\Actions\Core\BotUser\BotUserSetUnbanScheduler;
use App\Actions\Core\BotUser\BotUserUnban;
use App\Actions\Core\BotUserPrice\BotUserPriceGet;
use App\Actions\Core\PaySystemCallback\PaySystemCallbackCreate;
use App\Actions\Core\Product\ProductListByBot;
use App\Actions\Core\Telegram\TelegramWebhookInfo;
use App\Models\Core\BotMessage;
use App\Models\Core\BotMessageButton;
use App\Models\Core\GetcourseWebhook;
use App\Models\Core\TelegramSendMessageErrorLog;
use App\Models\Core\TelegramSendMessageLog;
use App\Models\Core\TelegramSendMessageSchedule;
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

use App\Actions\Core\Max\MaxQuery;

use App\Models\Core\Bot;
use App\Models\Core\MaxSendMessageLog;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\Core\User;
use App\Actions\Core\BotSendMessage\BotSendMessage;

use App\Actions\Core\Telegram\TelegramQuery;

class DevTestController extends Controller
{
    public function devtest() {
        $telegramQuery = new TelegramQuery();

        $bot_user = BotUser::find(7874);
        return $telegramQuery->handle($bot_user->bot,'getChat', ['chat_id' => 247632034]);
    }

    public function change_web_password(string $email) {
        $botSendMessage = new BotSendMessage();

        $user = User::where('email', $email)->first();
        if ($user) {
            $plainPassword = Str::password(8, true, true, false, false);
            $hashedPassword = Hash::make($plainPassword);

            User::where('id', $user->id)->update(['password' => $hashedPassword, 'open_password' => $plainPassword]);

            $bot_user = BotUser::where('email', $email)->first();
            if ($bot_user) {
                $botSendMessage->handle($bot_user, 'MAGICLIFE_WEB_ACCESS');
            }

            return $plainPassword;
        } else {
            return "Нет юзера с этой почтой";
        }

    }

}
