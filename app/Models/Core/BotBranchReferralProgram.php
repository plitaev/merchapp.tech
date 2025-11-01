<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class BotBranchReferralProgram extends Model
{
    protected $fillable = [
        'bot_branch_id',
        'referral_branch_id',
        'referrer_bot_user_id'
    ];
}
