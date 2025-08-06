<?php
namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MiniApp extends Model
{
    use HasFactory;

    protected $fillable = [
        'bot_id',
        'class_id',
        'name',
        'url'
    ];

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class);
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(MiniAppClass::class);
    }

}
