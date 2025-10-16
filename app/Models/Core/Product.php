<?php
namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'bot_id',
        'product_type_id',
        'name',
        'description',
        'price',
        'days',
        'credit'
    ];

    public function product_type(): BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class);
    }

}
