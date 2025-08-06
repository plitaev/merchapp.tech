<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TelegramBusinessOpeningLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'telegram_webhook_entrance_id',
        'telegram_webhook_responce_id',
        'event_name',
        'diff_in_minutes'
    ];

    public function telegram_webhook_entrance(): BelongsTo
    {
        return $this->belongsTo(TelegramWebhook::class, 'telegram_webhook_entrance_id', 'id');
    }

}
