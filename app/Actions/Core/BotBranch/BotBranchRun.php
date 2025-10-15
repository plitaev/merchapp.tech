<?php
namespace App\Actions\Core\BotBranch;

use App\Actions\Core\BotSendMessage\BotSendMessage;
use App\Actions\Core\BotUser\BotUserSetBranch;

use App\Models\Core\BotBranch;
use App\Models\Core\BotMessage;
use App\Models\Core\Pay;

class BotBranchRun
{
    public function handle($bot_user, string $hash) {
        $botMessage = new BotMessage();
        $botSendMessage = new BotSendMessage();
        $botUserSetBranch = new BotUserSetBranch();

        $branch = BotBranch::where('hash', $hash)
            ->where('datetime_start', '<=', date('Y-m-d H:i:s', time()))
            ->where('datetime_end', '>=', date('Y-m-d H:i:s', time()))
            ->first();

        if ($branch) {

            $newbie = 0;
            $pays = Pay::where('bot_user_id', $bot_user->id)->where('status', 1)->count();
            if ($bot_user->created_at >= $branch->datetime_start && $pays == 0) $newbie = 1;

            $guest = 0;
            $pays = Pay::where('bot_user_id', $bot_user->id)->where('status', 1)->count();
            if ($bot_user->created_at < $branch->datetime_start && $pays == 0) $guest = 1;


            $member = 0;
            $member_end = $bot_user->date_end." ".$bot_user->bot->ban_time;
            if (date('Y-m-d H:i:s', time()) > $member_end) $member = 1;

            $banned = $bot_user->ban;

            return $newbie." | ".$guest." | ".$member." | ".$banned;

            if ($newbie == 1) {
                if ($branch->new_users_bot_branch_access_id == 1) $botUserSetBranch->handle($bot_user, $hash);
                $bot_message = BotMessage::with('bot_message_appointment')->find($branch->new_users_bot_message_id);
                $botSendMessage->handle($bot_user, $bot_message->bot_message_appointment->alias);
            }

            if ($guest == 1) {
                if ($branch->guests_bot_branch_access_id == 1) $botUserSetBranch->handle($bot_user, $hash);
                $bot_message = BotMessage::with('bot_message_appointment')->find($branch->guests_bot_message_id);
                $botSendMessage->handle($bot_user, $bot_message->bot_message_appointment->alias);
            }

            if ($member == 1) {
                if ($branch->members_bot_branch_access_id == 1) $botUserSetBranch->handle($bot_user, $hash);
                $bot_message = BotMessage::with('bot_message_appointment')->find($branch->members_bot_message_id);
                $botSendMessage->handle($bot_user, $bot_message->bot_message_appointment->alias);
            }

            if ($banned == 1) {
                if ($branch->banneds_bot_branch_access_id == 1) $botUserSetBranch->handle($bot_user, $hash);
                $bot_message = BotMessage::with('bot_message_appointment')->find($branch->banneds_bot_message_id);
                $botSendMessage->handle($bot_user, $bot_message->bot_message_appointment->alias);
            }

            die();

        } else {
            $botUserSetBranch->handle($bot_user, 'BRANCH_MAIN');
        }
    }
}
