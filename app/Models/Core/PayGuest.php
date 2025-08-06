<?php
namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class PayGuest extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'email',
        'price',
        'days',
        'gift',
        'status',
        'recurrent',
        'recurrent_status'
    ];

    public function bot(): HasOneThrough {
        return $this->hasOneThrough(Bot::class, Product::class, 'id', 'id', 'product_id', 'bot_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function gift_name(): BelongsTo {
        return $this->belongsTo(Boolean::class, 'gift', 'id');
    }

}
