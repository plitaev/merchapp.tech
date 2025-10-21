<?php

namespace App\Actions\Core\BotBranch;

use App\Models\Core\BotBranchLinkProduct;

class BotBranchSetEndByProducts
{
    public function handle(int $bot_branch_id, array $product_ids) {
        $currents = BotBranchLinkProduct::select('product_id')->where('bot_branch_id', $bot_branch_id)->where('bot_branch_link_product_type_id', 1)->pluck('product_id')->toArray();

        foreach ($product_ids as $product_id) {
            if (!in_array($product_id, $currents)) {
                BotBranchLinkProduct::create(
                    [
                        'bot_branch_id' => $bot_branch_id,
                        'product_id' => $product_id,
                        'bot_branch_link_product_type_id' => 1
                    ]
                );
            }
        }

        BotBranchLinkProduct::where('bot_branch_id', $bot_branch_id)->where('bot_branch_link_product_type_id', 1)->whereNotIn('product_id', $product_ids)->delete();

    }
}
