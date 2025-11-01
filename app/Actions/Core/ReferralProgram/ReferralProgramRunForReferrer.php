<?php
namespace App\Actions\Core\ReferralProgram;

use App\Models\Core\BotBranch;

class ReferralProgramRunForReferrer
{
    public function handle($bot_user) {
        $date = date('Y-m-d', time());
        $datetime = date('Y-m-d H:i:s', time());

        $rp_actual = BotBranch::where('bot_branch_type', 3)
            ->where('bot_id', $bot_user->bot_id)
            ->where('datetime_start', '>=', $datetime)
            ->where('datetime_start', '<=', $datetime)
            ->first();

        if ($rp_actual) {

        }

    }
}
