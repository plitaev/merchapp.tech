<?php
namespace App\Actions\Core\ReferralProgram;

use App\Actions\Core\BotSendMessage\BotSendMessage;

use App\Models\Core\BotBranch;
use App\Models\Core\BotBranchReferralProgram;
use App\Models\Core\BotUser;
use App\Actions\Core\BotUser\BotUserSetBranch;

class ReferralProgramRunForReferral
{
    public function handle($bot_user, $branch_data) {
        $botSendMessage = new BotSendMessage();
        $botUserSetBranch = new BotUserSetBranch();

        //== Проверяем, что реферал - не участник клуба и никогда не был в клубе
        if ($bot_user->date_end) {
            $botSendMessage->handle($bot_user, 'SYS_RP_REFERRAL_IS_CLUB_MEMBER');
            die();
        }

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

        $referrals = BotBranchReferralProgram::where('bot_branch_id', $branch_data[1])
            ->where('referrer_bot_user_id', $branch_data[2])
            ->whereNotNull('referral_bot_user_id')
            ->count();

        if ($referrals >= $bot_branch->referal_program_max_referrals_count) {
            $botSendMessage->handle($bot_user, 'SYS_RP_REFERRAL_JOIN_LINK_FULL');
            die();
        }

        //== Проверяем, есть ли юзер в этой реф. программе, и если нет - прикрепляем его и назначаем referral_record_id
        $check_in_rp = BotBranchReferralProgram::select('id')->where('bot_branch_id', $branch_data[1])
            ->where('referrer_bot_user_id', $branch_data[2])
            ->where('referral_bot_user_id', $bot_user->id)
            ->first();

        if (!$check_in_rp) {

            $non_referral_record = BotBranchReferralProgram::select('id')
                ->where('bot_branch_id', $branch_data[1])
                ->where('referrer_bot_user_id', $branch_data[2])
                ->whereNull('referral_bot_user_id')
                ->first();

            if ($non_referral_record) {
                BotBranchReferralProgram::where('id', $non_referral_record->id)->update(['referral_bot_user_id' => $bot_user->id]);
                $referral_record_id = $non_referral_record->id;
            } else {

                $new_referral_record = BotBranchReferralProgram::create(
                    [
                        'bot_branch_id' => $branch_data[1],
                        'referrer_bot_user_id' => $branch_data[2],
                        'referral_bot_user_id' => $bot_user->id,
                        'referral_got_product_special' => 0,
                        'referral_got_product_full' => 0
                    ]
                );

                $referral_record_id = $new_referral_record->id;
            }

        } else {
            $referral_record_id = $check_in_rp->id;
        }

        BotUser::where('id', $bot_user->id)->update(['bot_branch_id' => $branch_data[1]]);

        //== Повторно достаем параметры из $referral_record, чтобы обновить их
        $referral_record = BotBranchReferralProgram::select('referral_got_product_special')->find($referral_record_id);

        //== Отправляем сообщение в зависимости от того, купил или нет реферрер акционный (реферальный) продукт
        if ($referral_record->referral_got_product_special == 0) {
            $botSendMessage->handle($bot_user, 'SYS_RP_REFERRAL_JOIN_LINK_SUCCESSFUL');
            die();
        } else {

            if ($bot_user->date_end >= date('Y-m-d', time())) {
                $botSendMessage->handle($bot_user, 'SYS_SUCCESS_MESSAGE');
            } else {
                $botSendMessage->handle($bot_user, 'SYS_USER_SUBSCRIPTION_DATA');
            }

            die();

        }

    }
}
