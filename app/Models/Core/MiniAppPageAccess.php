<?php
namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MiniAppPageAccess extends Model
{
    protected $fillable = [
        'name'
    ];
}
