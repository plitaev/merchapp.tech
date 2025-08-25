<?php
namespace App\Actions\Core\BotUser;
use App\Models\Core\BotUser;

class BotUserSetListener
{
    public function handle($field, $status, int $id) {
        BotUser::where('id', $id)->update([$field.'_status' => $status, $field.'_status_timestamp' => now()]);
    }
}
