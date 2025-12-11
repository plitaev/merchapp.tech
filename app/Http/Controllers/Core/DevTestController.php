<?php
namespace App\Http\Controllers\Core;

use App\Actions\Core\BotSendMessage\BotSendMessage;
use App\Http\Controllers\Controller;
use App\Models\Core\BotUser;
use App\Models\Core\BotUserSupergroupStatus;
use Illuminate\Support\Facades\DB;
use Telegram\Bot\Api;


class DevTestController extends Controller
{
    public function devtest() {
        $botSendMessage = new BotSendMessage();

        $A = [
            1692,
            1733,
            2856,
            3516,
            4696,
            4932,
            8121,
            14200,
            17810,
            18481,
            19771,
            19830,
            19997
        ];

        $bot_users = BotUser::whereIn('id', $A)->get();
        foreach ($bot_users as $bot_user) {
            $botSendMessage->handle($bot_user, 'BOT_PAYMENT_RECURRENT_FAIL');
        }

    }
}
