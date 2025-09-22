<?php

namespace App\Actions\Core\Auto;

class BotSetFunnels
{
    public function handle() {
        $res = BotMessage::with('funnel')->with('funnel_condition_trigger')->whereNotNull('funnel_condition_id')->whereNotNull('funnel_condition_trigger_id')->get();
        return $res;
    }
}
