<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class BotBranch extends Model
{
    protected $fillable = [
        'name',
        'alias',
        'hash',
        'new_users_bot_branch_access_id',
        'access_for_new_users_decline_bot_message_id',
        'guests_bot_branch_access_id',
        'access_for_guests_decline_bot_message_id',
        'members_bot_branch_access_id',
        'access_for_members_decline_bot_message_id',
        'banneds_bot_branch_access_id',
        'access_for_banneds_decline_bot_message_id'
    ];
}
