<?php

namespace App\Actions\Core\ReferralProgram;

use App\Models\Core\BotBranch;
use App\Models\Core\BotBranchReferralProgram;

class ReferralBuySpecialProduct
{
    public function handle($pay) {
        $datetime = date('Y-m-d H:i:s', time());

        $products = BotBranch::select('bot_branch_product_id')
            ->where('bot_branch_type', 3)
            ->where('datetime_start', '<=', $datetime)
            ->where('datetime_end', '>=', $datetime)
            ->whereNotNull('bot_branch_product_id')
            ->groupBy('bot_branch_product_id')
            ->pluck('bot_branch_product_id')
            ->toArray();

        if (in_array($pay->product_id, $products)) {

            $bot_branch = BotBranch::select('id')
                ->where('bot_branch_type', 3)
                ->where('datetime_start', '<=', $datetime)
                ->where('datetime_end', '>=', $datetime)
                ->where('product_id', $pay->product_id)
                ->first();

            if ($bot_branch) {
                $referral_record = BotBranchReferralProgram::select('id', 'referrer_bot_user_id')
                    ->where('bot_branch_id', $bot_branch->id)
                    ->where('referral_bot_user_id', $pay->bot_user_id)
                    ->where('referral_got_product_special', 0)
                    ->first();

                if ($referral_record) {
                    BotBranchReferralProgram::where('id', $referral_record->id)->update(['referral_got_product_special' => 1]);
                    //и тут кидаем сообщение реферреру
                }

            }

        }

    }
}
