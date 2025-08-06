<?php
namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BotMessage extends Model
{
    protected $fillable = [
        'bot_id',
        'bot_message_type_id',
        'bot_message_appointment_id',
        'funnel_id',
        'name',
        'text',
        'image',
        'video',
        'audio',
        'delete_through',
        'delete_through_hours',
        'delete_through_minutes',
        'delete_keyboard_through',
        'delete_keyboard_through_days',
        'delete_keyboard_through_hours',
        'delete_keyboard_through_minutes',
        'pause_after_message'
    ];

    public function bot_message_funnel(): BelongsTo
    {
        return $this->belongsTo(Funnel::class, 'funnel_id', 'id');
    }
    public function bot_message_appointment(): BelongsTo
    {
        return $this->belongsTo(BotMessageAppointment::class, 'bot_message_appointment_id', 'id');
    }

    public function bot_message_type(): BelongsTo
    {
        return $this->belongsTo(BotMessageType::class);
    }

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class);
    }

    public function bml(): BelongsTo
    {
        return $this->belongsTo(BotMessageListener::class);
    }

}
