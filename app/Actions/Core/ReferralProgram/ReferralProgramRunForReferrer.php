<?php

namespace App\Actions\Core\ReferralProgram;

use App\Actions\Core\BotSendMessage\BotSendMessage;

use App\Models\Core\BotBranch;
use App\Models\Core\BotBranchReferralProgram;

class ReferralProgramRunForReferrer
{
    public function handle($bot_user) {

        $botSendMessage = new BotSendMessage();

        $date = date('Y-m-d', time());
        $datetime = date('Y-m-d H:i:s', time());

        $rp_actual = BotBranch::select('id')
            ->where('bot_branch_type', 3)
            ->where('bot_id', $bot_user->bot_id)
            ->where('datetime_start', '>=', $datetime)
            ->where('datetime_start', '<=', $datetime)
            ->first();

        if ($rp_actual && isset($bot_user->date_end) && $bot_user->date_end >= $date) {

            BotBranchReferralProgram::create(
                [
                    'bot_branch_id' => $rp_actual->id,
                    'referral_bot_user_id' => $bot_user->id
                ]
            );

            $botSendMessage->handle($bot_user, 'SYS_RP_REFERRER_GENERATE_LINK');
        } else {
            $botSendMessage->handle($bot_user, 'SYS_RP_REFERRER_IS_NOT_A_MEMBER');
        }

    }
}
