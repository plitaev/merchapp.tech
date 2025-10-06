<?php
namespace App\Http\Controllers\Core;
use App\Actions\Core\DateEnd\DateEnd;
use App\Actions\Core\GetCourseWebhook\GetCourseWebhookCreate;
use App\Actions\Core\Telegram\TelegramChatJoinRequest;
use App\Http\Controllers\Controller;
use App\Models\Core\Bot;
use App\Models\Core\BotUser;
use App\Models\Core\BotUserBanSchedule;
use App\Models\Core\BotUserRecurrentSchedule;
use App\Models\Core\Pay;
use App\Models\Core\Product;
use App\Models\Core\TelegramSendMessageSchedule;
use App\Models\Core\TelegramWebhook;
use App\Models\Core\Sending;

use App\Actions\Core\BotSendMessage\BotSendMessage;

use YooKassa\Client;
use Telegram\Bot\Api;

use App\Models\Core\TelegramChatJoinRequestLog;
use App\Models\Core\GetcourseWebhook;

use Revolution\Google\Sheets\Facades\Sheets;

class DevTestController extends Controller
{
    public function devtest() {

        $res = DB::table('bot_user_ban_schedules_backup')->select('bot_user_id', 'run_status', 'ban_datetime')
            ->groupBy('bot_user_id', 'run_status', 'ban_datetime')
            ->orderBy('id')
            ->get();

        foreach ($res as $data) {

            $check = BotUserBanSchedule::where('ban_datetime', $data->ban_datetime)->where('bot_user_id', $data->bot_user_id)->count();

            if ($check == 0) {
                BotUserBanSchedule::create(
                    [
                        'bot_user_id' => $data->bot_user_id,
                        'run_status' => $data->run_status,
                        'ban_datetime' => $data->ban_datetime
                    ]
                );
            }

        }

        /*
        $sheetName = 'test';

        $data = [
            [
                'ID',
                'Name',
            ],
            [
                'U001',
                'John',
            ],
            [
                'U002',
                'Harry',
            ],
        ];

        Sheets::spreadsheet(config('google.post_spreadsheet_id'))->addSheet($sheetName);
        Sheets::sheet($sheetName)->append($data);
        */

        /*
        $result = [];

        $telegram = new Api('7427797340:AAEZd2WfiGalZ7EvAdRv2yCNkgTDwM7nVhY');

        $res = BotUser::where('date_end', '<=', '2025-10-04')->where('ban', 0)->get();

        foreach ($res as $data) {
            $status = $telegram->getChatMember(['chat_id' => -1002225281436, 'user_id' => $data->telegram_chat_id]);
            if ($status->status == 'member') {
                $result[] = $data->telegram_chat_id;
            }

        }

        return BotUser::whereIn('telegram_chat_id', $result)->where('date_end', '<', '2025-10-04')->get();
        */
    }
}
