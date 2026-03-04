<?php
namespace App\Http\Controllers\Core;

use App\Actions\Core\BotSupergroup\BotSupergroupsAll;
use App\Actions\Core\BotUser\BotUserUnban;
use App\Actions\Core\PaySystemCallback\PaySystemCallbackCreate;
use App\Models\Core\BotMessageButton;
use App\Models\Core\GetcourseWebhook;
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

class DevTestController extends Controller
{
    public function devtest() {

        BotUser::where('bot_id', 5)->update(['date_end' => '2026-03-04']);

        $bot_users = BotUser::where('bot_id', 5)->take(1)->get();
        foreach ($bot_users as $bot_user) {
            BotUserBanSchedule::create([
                'bot_user_id' => $bot_user->id,
                'run_status' => 0,
                'ban_datetime' => '2026-03-04 09:00:00',
            ]);
        }

        /*
        $telegram = new Api('7427797340:AAEZd2WfiGalZ7EvAdRv2yCNkgTDwM7nVhY');

        $bot_users = BotUser::where('run_status', 0)->skip(0)->take(5)->get();
        foreach ($bot_users as $bot_user) {
            BotUser::where('id', $bot_user->id)->update(['run_status' => 1]);
            $member = $telegram->getChatMember(['user_id' => $bot_user->telegram_chat_id, 'chat_id' => -1002602171099]);
            BotUser::where('id', $bot_user->id)->update(['access_bonus' => $member->status]);
        }
        */
        /*
        $bot_users = BotUser::whereNull('date_start')->where('date_end', '>=','2026-03-04')->get();
        $A = [];
        $AA = [];
        foreach ($bot_users as $bot_user) {
            $pays = Pay::where('status', 1)->where('bot_user_id', $bot_user->id)->get();
            if (count($pays) == 1) {
                $firstpay = Pay::where('status', 1)->where('bot_user_id', $bot_user->id)->first();
            } else {
                if ($bot_user->ban_time) {
                    $firstpay = Pay::where('status', 1)->where('bot_user_id', $bot_user->id)->where('created_at', '>=', $bot_user->ban_time)->orderBy('created_at')->first();
                } else {
                    $firstpay = Pay::where('status', 1)->where('bot_user_id', $bot_user->id)->first();
                }
            }

            if ($firstpay) {
                $date = Carbon::parse($firstpay->created_at)->format('Y-m-d');
                BotUser::where('id', $bot_user->id)->whereNull('date_start')->update(['date_start' => $date]);
            }

        }

        return $AA;
        */
    }

}
