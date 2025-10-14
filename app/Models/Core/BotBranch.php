<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class BotBranch extends Model
{
    protected $fillable = [
        'name',
        'alias',
        'hash',
        'access_for_new_users',
        'access_for_new_users_decline_bot_message_id',
        'access_for_guests',
        'access_for_guests_decline_bot_message_id',
        'access_for_members',
        'access_for_members_decline_bot_message_id',
        'access_for_banneds',
        'access_for_banneds_decline_bot_message_id'
    ];
}
