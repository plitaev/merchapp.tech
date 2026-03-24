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

use App\Actions\Core\Max\MaxQuery;

use App\Models\Core\Bot;

class DevTestController extends Controller
{
    public function devtest() {
        $maxQuery = new MaxQuery();

        $bot = Bot::find(2);
        $upload_url = $maxQuery->handle($bot, 'POST', 'uploads', [], true, ['type' => 'file']);
        $upload_url = $upload_url['url'];

        $cfile = curl_file_create(public_path().'/content/bot_message_videos/01KMFDV813EMZMHCJ1EV86CXVE.mp4', 'video/mp4', '01KMFDV813EMZMHCJ1EV86CXVE.mp4');


        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $upload_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ['data' => $cfile]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

}
