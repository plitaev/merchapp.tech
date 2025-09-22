<?php

namespace App\Actions\Core\Auto;

use App\Models\Core\BotMessage;

class BotSetFunnels
{
    public function handle() {
        $res = BotMessage::with('funnel_condition:id,alias')->with('funnel_condition_trigger:id,alias')
            ->select('id', 'funnel_condition_id', 'funnel_condition_trigger_id')
            ->whereNotNull('funnel_condition_id')->whereNotNull('funnel_condition_trigger_id')->get();
        return $res;
    }
}
