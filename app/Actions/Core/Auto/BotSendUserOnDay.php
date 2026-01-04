<?php

namespace App\Actions\Core\Auto;

use App\Models\Core\BotBranchReferralProgram;
use App\Models\Core\BotUser;
use App\Models\Core\Pay;
use Telegram\Bot\Api;

use App\Models\Core\BotMessage;
use App\Models\Core\StatBotUserOnDay;
use App\Models\Core\TelegramSupergroup;

class BotSendUserOnDay
{
    public function handle() {
        $stat = StatBotUserOnDay::orderByDesc('created_at')->first();
        $stat1490 = Pay::select('bot_user_id')->where('price', 1490)->where('status', 1)->groupBy('bot_user_id')->get();
        $stat1990 = Pay::select('bot_user_id')->where('price', 1990)->where('status', 1)->groupBy('bot_user_id')->get();

        $expireds1 = BotUser::where('date_end', '<', date('Y-m-d', time()))->whereNotNull('date_end')->count();
        $expireds2 = BotUser::whereNull('date_end')->where('listen_success_message_status', 1)->count();
        $expireds =$expireds1 + $expireds2;

        $date = date('Y-m-d', time());

        $bot_users_one_month_with_recurrent = [];
        $bot_users_one_month_without_recurrent = [];

        $bot_users = BotUser::where('date_end', '>=', $date)->get();
        foreach ($bot_users as $bot_user) {
            $last_pay = Pay::where('bot_user_id', $bot_user->id)->where('status', 1)->orderByDesc('created_at')->first();

            if ($last_pay && $last_pay->product_id == 1) {
                if ($bot_user->recurrent == 1) {
                    $bot_users_one_month_with_recurrent[] = $bot_user->id;
                } else {
                    $bot_users_one_month_without_recurrent[] = $bot_user->id;
                }
            }
        }

        $bot_users_one_month = count($bot_users_one_month_with_recurrent) + count($bot_users_one_month_without_recurrent);

        $pays_date = '2025-12-01 00:00:00';
        $pays_old = [];
        $pays_new = [];

        $pays = Pay::where('status', 1)->where('created_at', '>=', $pays_date)->get();
        foreach ($pays as $pay) {
            $old = Pay::where('bot_user_id', $pay->bot_user_id)->where('status', 1)->where('created_at', '<', $pays_date)->count();

            if ($old > 0) {
                $pays_old[] = $pay->id;
            } else {
                $pays_new[] = $pay->id;
            }

        }

        $bot_message = BotMessage::query()
            ->whereHas('bot_message_appointment', function ($query) {
                $query->where('alias', 'SYS_STAT_PER_DAY');
            })
            ->first();

        $text = $bot_message->text;
        $text = urldecode($text);

        $text = str_replace('VAR_STAT_USER_ON_DAY_DATE', date('d.m.Y', strtotime($stat->stat_date)), $text);

        //==
        $text = str_replace('VAR_STAT_USER_ON_DAY_COUNT', $stat->bot_user_count, $text);
        $text = str_replace('VAR_STAT_USER_1490', count($stat1490), $text);
        $text = str_replace('VAR_STAT_USER_1990', count($stat1990), $text);
        $text = str_replace('VAR_USER_EXPIRED', $expireds, $text);
        $text = str_replace('VAR_BOT_USERS_ONE_MONTH', $bot_users_one_month, $text);
        $text = str_replace('VAR_BOT_USERS_1_ONE_MONTH_WITH_RECURRENT', count($bot_users_one_month_with_recurrent), $text);
        $text = str_replace('VAR_BOT_USERS_2_ONE_MONTH_WITHOUT_RECURRENT', count($bot_users_one_month_without_recurrent), $text);
        $text = str_replace('VAR_PAYS_FROM_START_MONTH', count($pays), $text);
        $text = str_replace('VAR_PAYS_3_FROM_START_MONTH_NEW', count($pays_new), $text);
        $text = str_replace('VAR_PAYS_4_FROM_START_MONTH_OLD', count($pays_old), $text);

        //==

        $all_referrers_count = BotBranchReferralProgram::select('referrer_bot_user_id')->groupBy('referrer_bot_user_id')->get();
        $all_referrers_count = count($all_referrers_count);

        $all_referrers_percent = ($all_referrers_count*100)/$stat->bot_user_count;
        $all_referrers_percent = round($all_referrers_percent,1);

        $text = str_replace('VAR_ALL_REFERRERS_COUNT', $all_referrers_count, $text);
        $text = str_replace('VAR_ALL_REFERRERS_PERCENT', $all_referrers_percent."%", $text);

        //==

        $all_referrals_count = BotBranchReferralProgram::select('referral_bot_user_id')->whereNotNull('referral_bot_user_id')->groupBy('referral_bot_user_id')->get();
        $all_referrals_count = count($all_referrals_count);

        $all_referrals_percent = ($all_referrals_count*100)/$stat->bot_user_count;
        $all_referrals_percent = round($all_referrals_percent,1);

        $text = str_replace('VAR_ALL_REFERRALS_COUNT', $all_referrals_count, $text);
        $text = str_replace('VAR_ALL_REFERRALS_PERCENT', $all_referrals_percent."%", $text);

        //==

        $all_referrals_buyed_special_count = BotBranchReferralProgram::select('referral_bot_user_id')->whereNotNull('referral_bot_user_id')->where('referral_got_product_special', 1)->groupBy('referral_bot_user_id')->get();
        $all_referrals_buyed_special_count = count($all_referrals_buyed_special_count);

        $all_referrals_buyed_special_percent = ($all_referrals_buyed_special_count*100)/$stat->bot_user_count;
        $all_referrals_buyed_special_percent = round($all_referrals_buyed_special_percent,1);

        $text = str_replace('VAR_ALL_REFERRALS_BUYED_SPECIAL_COUNT', $all_referrals_buyed_special_count, $text);
        $text = str_replace('VAR_ALL_REFERRALS_BUYED_SPECIAL_PERCENT', $all_referrals_buyed_special_percent."%", $text);

        //==

        $all_referrals_buyed_full_count = BotBranchReferralProgram::select('referral_bot_user_id')->whereNotNull('referral_bot_user_id')->where('referral_got_product_full', 1)->groupBy('referral_bot_user_id')->get();
        $all_referrals_buyed_full_count = count($all_referrals_buyed_full_count);

        $all_referrals_buyed_full_percent = ($all_referrals_buyed_full_count*100)/$stat->bot_user_count;
        $all_referrals_buyed_full_percent = round($all_referrals_buyed_full_percent,1);

        $text = str_replace('VAR_ALL_REFERRALS_BUYED_FULL_COUNT', $all_referrals_buyed_full_count, $text);
        $text = str_replace('VAR_ALL_REFERRALS_BUYED_FULL_PERCENT', $all_referrals_buyed_full_percent."%", $text);

        //==


        $users_td = Pay::select('bot_user_id')->where('product_id', 27)->where('status', 1)->where('created_at', '>=', '2026-01-03 10:00:00')->groupBy('bot_user_id')->pluck('bot_user_id')->toArray();
        $fulls_td = Pay::whereNot('product_id', 27)->where('status', 1)->whereIn('bot_user_id', $users_td)->where('created_at', '>=', '2026-01-03 10:00:00')->count();
        $olds_for_td = Pay::select('bot_user_id')->where('status', 1)->where('created_at', '<', '2026-01-03 10:00:00')->groupBy('bot_user_id')->toArray();
        $users_td_without_olds = Pay::select('bot_user_id')->where('product_id', 27)->where('status', 1)->where('created_at', '>=', '2026-01-03 10:00:00')->whereNot('bot_user_id', $olds_for_td)->groupBy('bot_user_id')->toArray();

        $text = str_replace('VAR_TD_COUNT', count($users_td), $text);
        $text = str_replace('VAR_TD_TO_FULL_COUNT', $fulls_td, $text);
        $text = str_replace('VAR_TD_NEW_USERS', count($users_td_without_olds), $text);

        //==

        $supergroups = TelegramSupergroup::with('bot')->where('statistic_user_on_day', 1)->get();
        foreach ($supergroups as $supergroup) {
            $telegram = new Api($supergroup->bot->telegram_token);
            $telegram->sendMessage(['chat_id' => $supergroup->telegram_id, 'parse_mode' => 'HTML', 'text' => urldecode($text), 'protect_content' => true]);
        }

    }
}
