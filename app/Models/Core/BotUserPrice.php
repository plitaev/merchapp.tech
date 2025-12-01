<?php
namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class BotUserPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'bot_user_id',
        'product_id',
        'price'
    ];

}
