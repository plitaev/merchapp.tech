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

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DevTestController extends Controller
{
    public function devtest() {

        return BotUser::where('date_end', '>', '2025-09-22')->where('date_end', '<', '2025-10-10')->where('ban', 0)->get();

        $sheetName = 'test';

        $data = [
            [
                'ID',
                'Name',
            ],
            [
                'U001',
                'Hi',
            ],
            [
                'U002',
                'Magic',
            ],
        ];

        Sheets::spreadsheet(config('google.post_spreadsheet_id'))->addSheet($sheetName);
        Sheets::sheet($sheetName)->append($data);

    }
}
