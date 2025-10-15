<?php
namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

use App\Models\Core\Bot;
use App\Models\Core\BotUser;
use App\Models\Core\Boolean;

class BotUserBanSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'bot_user_id',
        'run_status',
        'ban_datetime'
    ];

    public function bot(): HasOneThrough {
        return $this->hasOneThrough(Bot::class, BotUser::class, 'id', 'id', 'bot_user_id', 'bot_id');
    }

    public function bot_user(): BelongsTo {
        return $this->belongsTo(BotUser::class, 'bot_user_id', 'id');
    }

    public function run_status_name(): BelongsTo
    {
        return $this->belongsTo(Boolean::class, 'unban_status', 'id');
    }

}
