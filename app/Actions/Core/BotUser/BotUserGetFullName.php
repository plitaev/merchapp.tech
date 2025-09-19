<?php

namespace App\Actions\Core\BotUser;

class BotUserGetFullName
{
    public function handle($bot_user) {
        return (isset($bot_user->first_name)?$bot_user->first_name:'').' '.(isset($bot_user->last_name)?$bot_user->last_name:'');
    }
}
