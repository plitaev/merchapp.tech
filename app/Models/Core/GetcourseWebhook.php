<?php
namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class GetcourseWebhook extends Model
{
    protected $fillable = [
        'product_id',
        'getcourse_id',
        'name',
        'email',
        'recurrent',
        'recurrent_status',
        'run_status',
        'created_at',
    ];


    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function bot(): HasOneThrough {
        return $this->hasOneThrough(Bot::class, Product::class, 'id', 'id', 'product_id', 'bot_id');
    }

    public function recurrent_name(): BelongsTo
    {
        return $this->belongsTo(Boolean::class, 'recurrent', 'id');
    }

    public function recurrent_status_name(): BelongsTo
    {
        return $this->belongsTo(Boolean::class, 'recurrent_status', 'id');
    }

}
