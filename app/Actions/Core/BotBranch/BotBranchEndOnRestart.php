<?php

namespace App\Actions\Core\BotBranch;

use App\Models\Core\BotBranch;
use App\Models\Core\BotUser;

class BotBranchEndOnRestart
{
    public function handle($bot_user) {
        $main_branch = BotBranch::select('id')->where('bot_id', $bot_user->bot_id)->where('hash', 'BRANCH_MAIN')->first();

        if ($main_branch) {
            $bot_branches = BotBranch::select('id')->where('end_by_restart', 1)->pluck('id')->toArray();

            if (in_array($bot_user->bot_branch_id, $bot_branches)) {
                BotUser::where('id', $bot_user->id)->update(['bot_branch_id' => $main_branch->id]);
            }

        }

    }
}
