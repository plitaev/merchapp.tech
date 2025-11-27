<?php

namespace App\Actions\Core\Auto;

use App\Actions\Core\Funnel\FunnelNotBuyBranchProduct;
use App\Actions\Core\Funnel\FunnelUserBan;
use App\Actions\Core\Funnel\FunnelUserBanWithRecurrent;
use App\Actions\Core\Funnel\FunnelUserBanWithoutRecurrent;
use App\Actions\Core\Funnel\FunnelReferrer;

use App\Models\Core\BotMessage;

class BotSetFunnels
{
    public function handle() {
        $funnelNotBuyBranchProduct = new FunnelNotBuyBranchProduct();
        $funnelUserBan = new FunnelUserBan();
        $funnelUserBanWithRecurrent = new FunnelUserBanWithRecurrent();
        $funnelUserBanWithoutRecurrent = new FunnelUserBanWithoutRecurrent();
        $funnelReferrer = new FunnelReferrer();

        $res = BotMessage::with('funnel_condition:id,alias')->with('funnel_condition_trigger:id,alias')->with('bot:id,ban_time,recurrent_time')
            ->select('id', 'funnel_condition_id', 'funnel_condition_trigger_id', 'funnel_days', 'funnel_hours', 'funnel_minutes', 'bot_id', 'bot_branch_id')
            ->whereNotNull('funnel_condition_id')
            ->whereNotNull('funnel_condition_trigger_id')
            ->where('id', 70)
            ->get();

        foreach ($res as $data) {
            $funnelNotBuyBranchProduct->handle($data);
            $funnelUserBan->handle($data);
            return $funnelUserBanWithRecurrent->handle($data);
            $funnelUserBanWithoutRecurrent->handle($data);
            $funnelReferrer->handle($data);
        }

    }
}
