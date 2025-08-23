<?php
namespace App\Actions\Core\BotUser;
use App\Models\Core\BotUser;

class BotUserGetByID
{
    public function handle(int $id) {
        return BotUser::find($id);
    }
}
