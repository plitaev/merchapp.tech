<?php
namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BotTemplateMessageButton extends Model
{

    protected $table = 'bot_template_message_buttons';

    protected $fillable = [
        'bot_template_message_id',
        'bot_message_button_type_id',
        'pay_system_id',
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

    public function pay_system(): BelongsTo
    {
        return $this->belongsTo(PaySystem::class, 'pay_system_id', 'id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

}
