<?php
namespace App\Actions\Core\BotUser;

use App\Models\Core\BotBranch;
use App\Models\Core\BotUser;

class BotUserSetBranch
{
    public function handle($bot_user, string $bot_branch_alias) {
        $bot_branch = BotBranch::select('id')->where('alias', $bot_branch_alias)->first();

        if ($bot_branch) {
            BotUser::where('id', $bot_user->id)->update(['bot_branch_id' => $bot_branch->id]);
        }

    }
}
