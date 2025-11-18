<?php
namespace App\Actions\Core\Funnel;

use Carbon\Carbon;

use App\Actions\Core\Funnel\FunnelGetDateTime;
use App\Actions\Core\TelegramSendMessageSchedule\GetUsersAlreadyInSendingToday;

use App\Models\Core\BotBranch;
use App\Models\Core\BotUser;
use App\Models\Core\Pay;
use App\Models\Core\Sending;
use App\Models\Core\TelegramSendMessageSchedule;

class FunnelNotBuyBranchProduct
{
    public function handle($data) {

        $funnelGetDateTime = new FunnelGetDateTime();
        $getUsersAlreadyInSendingToday = new GetUsersAlreadyInSendingToday();

        if ($data->funnel_condition->alias == "newbie_not_buy_branch_product") {

            $funnel_date_time = $funnelGetDateTime->handle($data);

            $date = $funnel_date_time['date'];
            $time = $funnel_date_time['time'];
            $datetime = $funnel_date_time['datetime'];

            if (date('H:i:s') >= $time) {
                $schedules = $getUsersAlreadyInSendingToday->handle($data);

                $bot_branch = BotBranch::select('bot_branch_product_id')->find($data->bot_branch_id);

                if ($bot_branch->bot_branch_product_id) {

                    $buyeds = Pay::select('bot_user_id')
                        ->where('product_id', $bot_branch->bot_branch_product_id)
                        ->where('status', 1)
                        ->groupBy('bot_user_id')
                        ->pluck('bot_user_id')
                        ->toArray();

                    $bot_users = BotUser::select('id')
                        ->whereNotIn('id', $schedules)
                        ->whereNotIn('id', $buyeds)
                        ->where('bot_branch_id', $bot_branch->bot_branch_product_id)
                        ->where('created_at', '<=', $datetime)
                        ->where('created_at', '<=', $date." 23:59:59")
                        ->get();

                    return $bot_users;
                    return Carbon::now()->format('Y-m-d H:i:s')." - ".Carbon::now()->subHours(3)->format('Y-m-d H:i:s')." - ".$datetime." | ".$date." | ".$time;

                    if (count($bot_users) > 0) {

                        $sending = Sending::create([
                            'bot_message_id' => $data->id,
                            'name' => 'Авторассылка по новичкам не купившим продукт акции',
                            'user_ban' => 0,
                            'send_datetime' => date('Y-m-d', time())." ".$time
                        ]);

                        foreach ($bot_users as $bot_user) {
                            TelegramSendMessageSchedule::create([
                                'sending_id' => $sending->id,
                                'bot_user_id' => $bot_user->id
                            ]);
                        }

                    }

                }

            }

        }

    }
}
