<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BotUserBranchLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'bot_user_id',
        'bot_branch_from_id',
        'bot_branch_to_id'
    ];

}
