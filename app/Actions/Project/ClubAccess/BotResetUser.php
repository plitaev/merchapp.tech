<?php
namespace App\Actions\Project\ClubAccess;

use App\Models\Core\BotUser;

class BotResetUser
{
    public function handle(int $id) {
        BotUser::where('id', $id)->update(
            [
                'listen_email_status' => 0,
                'listen_handname_status' => 0,
                'listen_check_access_status' => 0,
                'sys_welcome_message_status' => 0,
                'sys_go_to_pay_status' => 0,
                'sys_email_pay_not_found_first_status' => 0
            ]
        );
    }
}
