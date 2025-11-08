<?php

namespace App\Actions\Core\ReferralProgram;

use App\Actions\Core\BotSendMessage\BotSendMessage;
use App\Actions\Core\Pay\PayCreateByEmail;

use App\Models\Core\BotBranch;
use App\Models\Core\BotBranchReferralProgram;
use App\Models\Core\BotUser;

class ReferralBuySpecialProduct
{
    public function handle($pay) {
        $datetime = date('Y-m-d H:i:s', time());

        $botSendMessage = new BotSendMessage();
        $payCreateByEmail = new PayCreateByEmail();

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
                ->where('bot_branch_product_id', $pay->product_id)
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
                    $referrals_count = BotBranchReferralProgram::query()
                        ->where('bot_branch_id', $bot_branch->id)
                        ->where('referrer_bot_user_id', $referral_record->referrer_bot_user_id)
                        ->whereNotNull('referral_bot_user_id')
                        ->where('referral_got_product_special', 1)
                        ->count();

                    $referrer_bot_user = BotUser::find($referral_record->referrer_bot_user_id);

                    $botSendMessage->handle($referrer_bot_user, 'SYS_RP_REFERRER_NOTICE_WHEN_REFERAL_JOIN_'.$referrals_count);
                }

            }

        } else {

            $check_is_active_referral = BotBranchReferralProgram::select('id', 'bot_branch_id', 'referrer_bot_user_id')
                ->where('referral_bot_user_id', $pay->bot_user_id)
                ->where('referral_got_product_special', 1)
                ->where('referral_got_product_full', 0)
                ->first();

            if ($check_is_active_referral) {
                $bot_branch = BotBranch::select('referal_program_product_id_for_referrer')
                    ->find($check_is_active_referral->bot_branch_id);

                if ($bot_branch) {
                    $bot_user_referrer = BotUser::select('email')->find($check_is_active_referral->referrer_bot_user_id);
                    $payCreateByEmail->handle($bot_user_referrer->email, $bot_branch->referal_program_product_id_for_referrer, 0, 0);
                }

            }

        }

    }
}
