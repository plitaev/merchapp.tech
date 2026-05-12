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

use App\Models\Core\BotUserUnbanSchedule;

class DevTestController extends Controller
{
    public function devtest() {
        /*
        $ids = BotUser::select('id')->where('date_end', '>=', date('Y-m-d', time()))->pluck('id')->toArray();

        BotUserBanSchedule::where('ban_datetime', '2026-05-10 23:30:00')->whereNotIn('bot_user_id', $ids)->update(['run_status' => 0]);
        BotUserBanSchedule::where('ban_datetime', '2026-05-09 23:30:00')->whereNotIn('bot_user_id', $ids)->update(['run_status' => 0]);
        BotUserBanSchedule::where('ban_datetime', '2026-05-08 23:30:00')->whereNotIn('bot_user_id', $ids)->update(['run_status' => 0]);
        BotUserBanSchedule::where('ban_datetime', '2026-05-07 23:30:00')->whereNotIn('bot_user_id', $ids)->update(['run_status' => 0]);
        BotUserBanSchedule::where('ban_datetime', '2026-05-06 23:30:00')->whereNotIn('bot_user_id', $ids)->update(['run_status' => 0]);
        BotUserBanSchedule::where('ban_datetime', '2026-05-05 23:30:00')->whereNotIn('bot_user_id', $ids)->update(['run_status' => 0]);

        $bot_users = BotUser::where('date_end', '>=', '2026-05-10')->where('ban', 1)->get();
        foreach ($bot_users as $bot_user) {
            BotUserUnbanSchedule::create(
                [
                    'bot_user_id' => $bot_user->id,
                    'run_status' => 0,
                    'unban_datetime' => date('Y-m-d H:i:s', time())
                ]
            );
        }
        */

        $botSendMessage = new BotSendMessage();

        $bot_user = BotUser::where('id', 2227)->first();

        return $botSendMessage->handle($bot_user, 'SYS_SUCCESS_MESSAGE', 'max');

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
