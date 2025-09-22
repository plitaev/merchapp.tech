<?php

namespace App\Actions\Core\Auto;

use App\Actions\Core\Funnel\FunnelUserBan;

use App\Models\Core\BotMessage;

class BotSetFunnels
{
    public function handle() {
        $funnelUserBan = new FunnelUserBan();

        $res = BotMessage::with('funnel_condition:id,alias')->with('funnel_condition_trigger:id,alias')
            ->select('id', 'funnel_condition_id', 'funnel_condition_trigger_id', 'funnel_days', 'funnel_hours', 'funnel_minutes')
            ->whereNotNull('funnel_condition_id')
            ->whereNotNull('funnel_condition_trigger_id')
            ->where('id', 15)
            ->get();

        return $res;

        foreach ($res as $data) {
            return $funnelUserBan->handle($data);
        }

    }
}
