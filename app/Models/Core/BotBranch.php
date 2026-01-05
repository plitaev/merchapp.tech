<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class BotBranch extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'bot_id',
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

    public function bot_message(): BelongsTo
    {
        return $this->belongsTo(BotMessage::class);
    }
}
