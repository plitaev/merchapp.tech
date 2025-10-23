<?php

namespace App\Actions\Core\BotBranch;

use App\Models\Core\BotBranchLinkProduct;
use App\Models\Core\BotUser;

class BotBranchEndByProducts
{
    public function handle(int $product_id, $bot_user_id) {

        $branches = BotBranchLinkProduct::select('bot_branch_id')
            ->where('product_id', $product_id)
            ->where('bot_branch_link_product_type_id', 1)
            ->groupBy('bot_branch_id')
            ->pluck('bot_branch_id')
            ->toArray();

        if (count($branches) > 0) {
            BotUser::where('id', $bot_user_id)->whereIn('bot_branch_id', $branches)->update(['bot_branch_id' => 1]);
        }

    }
}
