<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class BotTemplateBranch extends Model
{
    protected $fillable = [
        'bot_template_id',
        'bot_branch_type',
        'bot_branch_product_id',
        'name',
        'alias',
        'hash',
        'datetime_start',
        'datetime_end',
        'end_by_restart',
        'new_users_bot_branch_access_id',
        'new_users_bot_message_id',
        'guests_bot_branch_access_id',
        'guests_bot_message_id',
        'members_bot_branch_access_id',
        'members_bot_message_id',
        'banneds_bot_branch_access_id',
        'banneds_bot_message_id',
        'referal_program_max_referrals_count',
        'referal_program_product_id_for_referrer'
    ];
}
