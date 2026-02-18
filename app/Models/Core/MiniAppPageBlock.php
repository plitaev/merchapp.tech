<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class MiniAppPageBlock extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'mini_app_page_id',
        'block_type_id',
        'position'
    ];

    public function miniapp_page(): BelongsTo
    {
        return $this->belongsTo(MiniAppPage::class, 'mini_app_page_id', 'id');
    }
    public function block_type(): BelongsTo
    {
        return $this->belongsTo(MiniAppBlockType::class, 'block_type_id', 'id');
    }
}
