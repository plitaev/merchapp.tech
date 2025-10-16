<?php
namespace App\Actions\Core\BotUser;

use App\Actions\Project\ClubAccess\BotResetUser;

use App\Models\Core\BotBranch;
use App\Models\Core\BotUser;

class BotUserSetBranch
{
    public function handle($bot_user, string $hash) {
        $bot_reset_user = new BotResetUser();
        $bot_branch = BotBranch::select('id')->where('hash', $hash)->first();

        if ($bot_branch) {
            $bot_reset_user->handle($bot_user->id);
            BotUser::where('id', $bot_user->id)->update(['bot_branch_id' => $bot_branch->id]);
        }

    }
}
