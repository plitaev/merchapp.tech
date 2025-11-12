<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BotBranchReferrerSending extends Model
{
    protected $fillable = [
        'name',
        'alias'
    ];
}
