<?php
namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

use App\Models\Core\Bot;
use App\Models\Core\BotUser;

class BotUserUnbanSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'bot_user_id',
        'run_status',
        'unban_date',
        'unban_time'
    ];

    public function bot(): HasOneThrough {
        return $this->hasOneThrough(Bot::class, BotUser::class, 'id', 'id', 'bot_user_id', 'bot_id');
    }

    public function bot_user(): BelongsTo {
        return $this->belongsTo(BotUser::class, 'bot_user_id', 'id');
    }

}
