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
            ->where('datetime_start', '<=', $datetime)
            ->where('datetime_end', '>=', $datetime)
            ->orderByDesc('datetime_start')
            ->first();

        if ($rp_actual) {

            if (isset($bot_user->date_end) && $bot_user->date_end >= $date) {

                $check = BotBranchReferralProgram::where('bot_branch_id', $rp_actual->id)->where('referrer_bot_user_id', $bot_user->id)->count();

                if ($check == 0) {
                    BotBranchReferralProgram::create(
                        [
                            'bot_branch_id' => $rp_actual->id,
                            'referrer_bot_user_id' => $bot_user->id
                        ]
                    );
                }

                $botSendMessage->handle($bot_user, 'SYS_RP_REFERRER_GENERATE_LINK');

            } else {
                $botSendMessage->handle($bot_user, 'SYS_RP_REFERRER_IS_NOT_A_MEMBER');
            }

        } else {
            $botSendMessage->handle($bot_user, 'SYS_RP_EXPIRED');
        }

    }
}
