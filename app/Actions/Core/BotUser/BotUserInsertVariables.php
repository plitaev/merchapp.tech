<?php

namespace App\Actions\Core\BotUser;

use App\Models\Core\BotBranch;
use App\Models\Core\BotBranchReferralProgram;
use App\Models\Core\BotUser;
use App\Models\Core\BotUserPrice;

class BotUserInsertVariables {

    public function handle($bot_user, $text) {
        //== Адрес почты

        if (stripos(strtolower($text), 'VAR_USER_EMAIL')) $text = str_replace('VAR_USER_EMAIL', $bot_user->email, $text);

        //== Имя пользователя
        if (stripos(strtolower($text), 'VAR_USERNAME')) {
            if ($bot_user->username!="none") $text = str_replace('VAR_USERNAME', "@".$bot_user->username, $text);
        }

        if (stripos(strtolower($text), 'VAR_USER_FULL_NAME')) {
            $message_dd = "";

            if ($bot_user->first_name && $bot_user->first_name!="none") $message_dd = $bot_user->first_name;
            if ($bot_user->last_name && $bot_user->last_name!="none") $message_dd = $message_dd." ".$bot_user->last_name;
            if ($bot_user->first_name || $bot_user->last_name || $bot_user->first_name!="none" || $bot_user->last_name!="none") $message_dd = ", ".$message_dd;

            $text = str_replace('VAR_USER_FULL_NAME', $message_dd, $text);
        }

        if (stripos(strtolower($text), 'VAR_USER_DATE_END')) {
            $bot_user = BotUser::find($bot_user->id);

            $date_end = date('d.m.Y', strtotime($bot_user->date_end));
            if ($date_end == '01.01.1970') $date_end = '';
            $text = str_replace('VAR_USER_DATE_END', $date_end, $text);
        }

        if (stripos(strtolower($text), 'VAR_RP_REFERRER_LINK')) {
            $rp = BotBranchReferralProgram::whereHas('bot_branch', function ($query) use ($bot_user) {
                $query->where('bot_branch_type', 3);
            })
                ->where('referrer_bot_user_id', $bot_user->id)
                ->orderByDesc('bot_branch_referral_programs.created_at')
                ->first();

            if ($rp) {
                $link = "https://t.me/".$bot_user->bot->alias."?start=".base64_encode("3|".$rp->bot_branch_id."|".$bot_user->id);
                $text = str_replace('VAR_RP_REFERRER_LINK', $link, $text);
            }
        }

        if (stripos(strtolower($text), '[VAR_PRODUCT_PRICE_')) {
            $A = explode('VAR_PRODUCT_PRICE_', $text);
            $A = explode(']', $A[1]);
            $product_id = $A[0];

            $bot_user_price = BotUserPrice::select('price')->where('bot_user_id', $bot_user->id)->where('product_id', $product_id)->first();
            if ($bot_user_price) {
                $text = str_replace('[VAR_PRODUCT_PRICE_'.$product_id.']', $bot_user_price->price.'₽', $text);
            } else {
                $product = BotUserPrice::select('price')->find($product_id);
                $text = str_replace('[VAR_PRODUCT_PRICE_'.$product_id.']', $product->price.'₽', $text);
            }

        }

        return $text;
    }

}
