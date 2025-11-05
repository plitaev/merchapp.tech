<?php
namespace App\Actions\Core\ReferralProgram;

use App\Actions\Core\BotSendMessage\BotSendMessage;

use App\Models\Core\BotBranch;
use App\Models\Core\BotBranchReferralProgram;

class ReferralProgramRunForReferral
{
    public function handle($bot_user, $branch_data) {
        $botSendMessage = new BotSendMessage();

        //== Проверяем, проходил ли юзер по реф.ссылке другого пользователя
        $check_in_other_rp = BotBranchReferralProgram::whereNot('referrer_bot_user_id', $branch_data[2])
            ->where('bot_branch_id', $branch_data[1])
            ->where('referral_bot_user_id', $bot_user->id)
            ->count();

        if ($check_in_other_rp > 0) {
            $botSendMessage->handle($bot_user, 'SYS_RP_REFERRAL_JOIN_LINK_WHEN_ALREADY_JOINED');
            die();
        }

        //== Если реферрер прошел сам по своей ссылке
        if ($bot_user->id == $branch_data[2]) {
            $botSendMessage->handle($bot_user, 'SYS_RP_REFERRER_GO_TO_HIS_LINK');
            die();
        }

        //== Проверяем, сколько людей уже прошло по ссылке
        $bot_branch = BotBranch::select('referal_program_max_referrals_count')->find($branch_data[1]);
        $referrals = BotBranchReferralProgram::where('bot_branch_id', $branch_data[1])->where('referrer_bot_user_id', $branch_data[2])->whereNotNull('referral_bot_user_id')->count();

        if ($referrals >= $bot_branch->referal_program_max_referrals_count) {
            $botSendMessage->handle($bot_user, 'SYS_RP_REFERRAL_JOIN_LINK_FULL');
            die();
        }

        //== Проверяем, есть ли юзер в этой реф. программе, и если нет - прикрепляем его
        $check_in_rp = BotBranchReferralProgram::where('bot_branch_id', $branch_data[1])
            ->where('referral_bot_user_id', $bot_user->id)
            ->where('referrer_bot_user_id', $branch_data[2])
            ->count();

        if ($check_in_rp == 0) {
            $non_referral_record = BotBranchReferralProgram::select('id')
                ->where('bot_branch_id', $branch_data[1])
                ->where('referrer_bot_user_id', $branch_data[2])
                ->whereNull('referral_bot_user_id')
                ->first();

            if ($non_referral_record) {
                BotBranchReferralProgram::where('id', $non_referral_record->id)->update(['referral_bot_user_id' => $bot_user->id]);
            }

        }

    }
}
