<?php
namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use App\Models\Core\BotUser;
use App\Models\Core\BotUserSupergroupStatus;
use Illuminate\Support\Facades\DB;
use Telegram\Bot\Api;


class DevTestController extends Controller
{
    public function devtest() {
        $telegram = new Api('7427797340:AAEZd2WfiGalZ7EvAdRv2yCNkgTDwM7nVhY');

        $ids = BotUserSupergroupStatus::select('bot_user_id')
            ->where('telegram_supergroup_id', 3)
            ->where('status', 'member')
            ->pluck('bot_user_id')
            ->toArray();

        $A = [];

        $bot_users = BotUser::whereIn('id', $ids)->get();

        foreach ($bot_users as $bot_user) {
            $status = $telegram->banChatMember(['chat_id' => -1002602171099, 'user_id' => $bot_user->telegram_chat_id]);

            $A[] = $bot_user->id.' - '.$status;

        }

        return $A;
    }
}
