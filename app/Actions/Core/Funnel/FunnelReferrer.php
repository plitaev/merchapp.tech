<?php
namespace App\Actions\Core\Funnel;

use App\Actions\Core\TelegramSendMessageSchedule\GetUsersAlreadyInSendingToday;
use App\Actions\Core\Funnel\FunnelGetDateTime;

use App\Models\Core\BotBranchReferralProgram;
use App\Models\Core\Sending;
use App\Models\Core\TelegramSendMessageSchedule;

class FunnelReferrer
{
    public function handle($data) {

        $funnelGetDateTime = new FunnelGetDateTime();
        $getUsersAlreadyInSendingToday = new GetUsersAlreadyInSendingToday();

        if ($data->funnel_condition->alias == "referrer_with_no_referrals") {
            $funnel_date_time = $funnelGetDateTime->handle($data);

            $date = $funnel_date_time['date'];
            $time = $funnel_date_time['time'];
            $datetime = $funnel_date_time['datetime'];

            if (date('H:i:s') >= $time) {
                $schedules = $getUsersAlreadyInSendingToday->handle($data);

                $referrers_with_referrals = BotBranchReferralProgram::select('referrer_bot_user_id')
                    ->where('bot_branch_id', $data->bot_branch_id)
                    ->whereNotNull('referral_bot_user_id')
                    ->groupBy('referrer_bot_user_id')
                    ->pluck('referrer_bot_user_id')
                    ->toArray();

                    $referrers = BotBranchReferralProgram::select('referrer_bot_user_id')
                        ->where('bot_branch_id', $data->bot_branch_id)
                        ->whereNotIn('referrer_bot_user_id', $referrers_with_referrals)
                        ->whereNotIn('referrer_bot_user_id', $schedules)
                        ->whereNull('referral_bot_user_id')
                        ->where('created_at', '>=', $datetime)
                        ->where('created_at', '<=', $date." 23:59:59")
                        ->groupBy('referrer_bot_user_id')
                        ->get();

                    if (count($referrers) > 0) {

                        $sending = Sending::create([
                            'bot_message_id' => $data->id,
                            'name' => 'Авторассылка по реферрерам без рефералов',
                            'user_ban' => 0,
                            'send_datetime' => date('Y-m-d', time())." ".$time
                        ]);

                        foreach ($referrers as $referrer) {
                            TelegramSendMessageSchedule::create([
                                'sending_id' => $sending->id,
                                'bot_user_id' => $referrer->referrer_bot_user_id
                            ]);
                        }

                    }
            }
        }

    }
}
