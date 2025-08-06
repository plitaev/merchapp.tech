<?php
namespace App\Actions\Core\Telegram;

use App\Models\Core\BotUser;
use App\Models\Core\TelegramInviteLinkLog;
use App\Models\Core\TelegramSupergroup;

class TelegramInviteLink
{
    public function handle($bot_user, $telegram) {

        if ($bot_user->telegram_invite_link) {
            return $bot_user->telegram_invite_link;
        } else {
            $supergroup = TelegramSupergroup::select('telegram_id')->where('bot_id', $bot_user->bot_id)->where('give_access', 1)->first();
            $link = $telegram->createChatInviteLink(['chat_id' => $supergroup->telegram_id, 'member_limit' => 1]);

            BotUser::where('id', $bot_user->id)->update(['telegram_invite_link' => $link->invite_link]);

            TelegramInviteLinkLog::create(
                [
                    'bot_user_id' => $bot_user->id,
                    'chat_id' => $bot_user->telegram_chat_id,
                    'invite_link' => $link->invite_link,
                    'telegram_data' => json_encode($link, true),
                    'invite_link_action_id' => 1
                ]
            );

            return $link->invite_link;
        }

    }
}
