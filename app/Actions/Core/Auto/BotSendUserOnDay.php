<?php

namespace App\Actions\Core\Auto;

use App\Models\Core\BotBranchReferralProgram;
use Telegram\Bot\Api;

use App\Models\Core\BotMessage;
use App\Models\Core\StatBotUserOnDay;
use App\Models\Core\TelegramSupergroup;

class BotSendUserOnDay
{
    public function handle() {
        $stat = StatBotUserOnDay::orderByDesc('created_at')->first();

        $bot_message = BotMessage::query()
            ->whereHas('bot_message_appointment', function ($query) {
                $query->where('alias', 'SYS_STAT_PER_DAY');
            })
            ->first();

        $text = $bot_message->text;
        $text = urldecode($text);

        $text = str_replace('VAR_STAT_USER_ON_DAY_DATE', date('d.m.Y', strtotime($stat->stat_date)), $text);
        $text = str_replace('VAR_STAT_USER_ON_DAY_COUNT', $stat->bot_user_count, $text);

        //==

        $all_referrers_count = BotBranchReferralProgram::select('referrer_bot_user_id')->groupBy('referrer_bot_user_id')->get();
        $all_referrers_count = count($all_referrers_count);
        $all_referrers_percent = ($all_referrers_count*100)/$stat->bot_user_count;

        $text = str_replace('VAR_ALL_REFERRERS_COUNT', $all_referrers_count, $text);
        $text = str_replace('VAR_ALL_REFERRERS_PERCENT', $all_referrers_percent."%", $text);

        //==

        $all_referrals_count = BotBranchReferralProgram::select('referral_bot_user_id')->whereNotNull('referral_bot_user_id')->groupBy('referral_bot_user_id')->count();
        $all_referrals_percent = ($all_referrals_count*100)/$stat->bot_user_count;

        $text = str_replace('VAR_ALL_REFERRALS_COUNT', $all_referrals_count, $text);
        $text = str_replace('VAR_ALL_REFERRALS_PERCENT', $all_referrals_percent."%", $text);

        //==

        $all_referrals_buyed_special_count = BotBranchReferralProgram::select('referral_bot_user_id')->whereNotNull('referral_bot_user_id')->where('referral_got_product_special', 1)->groupBy('referral_bot_user_id')->count();
        $all_referrals_buyed_special_percent = ($all_referrals_buyed_special_count*100)/$stat->bot_user_count;

        $text = str_replace('VAR_ALL_REFERRALS_BUYED_SPECIAL_COUNT', $all_referrals_buyed_special_count, $text);
        $text = str_replace('VAR_ALL_REFERRALS_BUYED_SPECIAL_PERCENT', $all_referrals_buyed_special_percent."%", $text);

        //==

        $all_referrals_buyed_full_count = BotBranchReferralProgram::select('referral_bot_user_id')->whereNotNull('referral_bot_user_id')->where('referral_got_product_full', 1)->groupBy('referral_bot_user_id')->count();
        $all_referrals_buyed_full_percent = ($all_referrals_buyed_full_count*100)/$stat->bot_user_count;

        $text = str_replace('VAR_ALL_REFERRALS_BUYED_FULL_COUNT', $all_referrals_buyed_full_count, $text);
        $text = str_replace('VAR_ALL_REFERRALS_BUYED_FULL_PERCENT', $all_referrals_buyed_full_percent."%", $text);

        //==

        $supergroups = TelegramSupergroup::with('bot')->where('statistic_user_on_day', 1)->get();
        foreach ($supergroups as $supergroup) {
            $telegram = new Api($supergroup->bot->telegram_token);
            $telegram->sendMessage(['chat_id' => $supergroup->telegram_id, 'parse_mode' => 'HTML', 'text' => urldecode($text), 'protect_content' => true]);
        }

    }
}
