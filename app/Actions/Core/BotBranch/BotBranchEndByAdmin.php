<?php

namespace App\Actions\Core\BotBranch;

use App\Models\Core\BotBranch;
use App\Models\Core\BotUser;
use App\Models\Core\BotUserBranchLog;

class BotBranchEndByAdmin
{
    public function handle(int $bot_id, int $bot_branch_id) {
        $bot_branch = BotBranch::select('id')->where('bot_id', $bot_id)->where('bot_branch_type', 1)->where('alias', 'BRANCH_MAIN')->first();

        if ($bot_branch) {
            $bot_users = BotUser::select('id')->where('bot_branch_id', $bot_branch_id)->pluck('id')->toArray();

            foreach ($bot_users as $bot_user_id) {
                BotUserBranchLog::create(
                    [
                        'bot_user_id' => $bot_user_id,
                        'bot_branch_from_id' => $bot_branch_id,
                        'bot_branch_to_id' => $bot_branch->id
                    ]
                );
            }

            BotUser::whereIn('id', $bot_users)->update(['bot_branch_id' => $bot_branch->id]);
            BotBranch::where('id', $bot_branch_id)->update(['datetime_end' => date('Y-m-d H:i:s', time())]);
        }

    }
}
