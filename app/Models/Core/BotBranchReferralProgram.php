<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BotBranchReferralProgram extends Model
{
    protected $fillable = [
        'bot_branch_id',
        'referrer_bot_user_id',
        'referral_bot_user_id'
    ];

    public function bot_branch(): BelongsTo
    {
        return $this->belongsTo(BotBranch::class, 'bot_branch_id', 'id');
    }

}
