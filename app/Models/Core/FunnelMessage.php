<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FunnelMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'funnel_id',
        'bot_message_id',
        'funnel_condition_id',
        'days_before_condition',
        'hours_before_condition',
        'minutes_before_condition',
        'days_after_condition',
        'hours_after_condition',
        'minutes_after_condition',
    ];

    public function funnel(): BelongsTo
    {
        return $this->belongsTo(Funnel::class, 'funnel_id', 'id');
    }

    public function bot_message(): BelongsTo
    {
        return $this->belongsTo(BotMessage::class, 'bot_message_id', 'id');
    }

    public function funnel_condition(): BelongsTo
    {
        return $this->belongsTo(FunnelCondition::class, 'funnel_condition_id', 'id');
    }
}
