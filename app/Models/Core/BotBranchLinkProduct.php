<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BotBranchLinkProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'bot_branch_id',
        'product_id',
        'bot_branch_link_product_type_id'
    ];
}
