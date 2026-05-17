<?php
namespace App\Http\Controllers\Core;
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
        $botSendMessage = new BotSendMessage();

        $bot_user = BotUser::with('bot')->where('id', 7874)->first();
        return $botSendMessage->handle($bot_user, 'SYS_SUCCESS_MESSAGE', 'telegram');
    }
}
