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

class DevTestController extends Controller
{
    public function devtest()
    {
        $maxQuery = new MaxQuery();
        $bot_user = BotUser::find(7874);

        $A = [];
        $A['user_ids'] = [$bot_user->max_user_id];

        return $maxQuery->handle($bot_user->bot, 'POST', 'chats/-73398711907001/members', $A, false, ['user_id' => $bot_user->max_user_id]);
    }
}
