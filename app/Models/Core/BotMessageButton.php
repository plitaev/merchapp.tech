<?php
namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BotMessageButton extends Model
{
    protected $fillable = [
        'bot_message_id',
        'bot_message_button_type_id',
        'product_id',
        'name',
        'url',
        'bot_message_callback_id',
        'callback',
        'tracking',
        'pos'
    ];

    public function  bot_message_button_callbacks(): BelongsTo
    {
        return $this->belongsTo(BotMessageButtonCallback::class, 'bot_message_callback_id', 'id');
    }

}
