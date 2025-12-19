<?php
namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BotUserTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'bot_user_id',
        'pay_id'
    ];
}
