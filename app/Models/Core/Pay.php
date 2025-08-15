<?php
namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Pay extends Model
{
    protected $fillable = [
        'product_id',
        'gift',
        'bot_user_id',
        'gift_bot_user_id',
        'price',
        'days',
        'status',
        'recurrent',
        'recurrent_status',
        'pay_system_id',
        'pay_system_callback',
        'pay_system_comission',
        'provider_payment_charge_id',
        'telegram_payment_charge_id'
    ];

    public function bot_user(): BelongsTo {
        return $this->belongsTo(BotUser::class, 'bot_user_id', 'id');
    }

    public function product(): BelongsTo {
        return $this->belongsTo(Product::class);
    }

    public function bot(): HasOneThrough {
        return $this->hasOneThrough(Bot::class, Product::class, 'id', 'id', 'product_id', 'bot_id');
    }

    public function product_type(): HasOneThrough {
        return $this->hasOneThrough(ProductType::class, Product::class, 'id', 'id', 'product_id', 'product_type_id');
    }

    public function recurrent_name(): BelongsTo {
        return $this->belongsTo(Boolean::class, 'recurrent', 'id');
    }

    public function recurrent_status_name(): BelongsTo {
        return $this->belongsTo(Boolean::class, 'recurrent_status', 'id');
    }

}
