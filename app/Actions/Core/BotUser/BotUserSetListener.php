<?php

namespace App\Actions\Core\BotUser;

class BotUserSetListener
{
    public function handle($field, $status, int $id) {
        \App\Models\Core\BotUser::where('id', $id)->update([$field.'_status' => $status, $field.'_status_timestamp' => now()]);
    }
}
