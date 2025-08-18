<?php
namespace App\Actions\Core\PaySystemCallback;

use App\Models\Core\PaySystem;
use App\Models\Core\PaySystemCallback;

class PaySystemCallbackCreate
{
    public function handle(string $callback, string $pay_system_alias) {
        $pay_system = PaySystem::where('alias', $pay_system_alias)->first();
        if ($pay_system)PaySystemCallback::create(['pay_system_id' => $pay_system->id, 'callback' => $callback]);
    }
}
