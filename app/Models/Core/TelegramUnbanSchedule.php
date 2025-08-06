<?php

namespace App\Models\Core;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TelegramUnbanSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'bot_id',
        'chat_id',
        'run_status',
        'unban_status',
    ];

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class, 'bot_id', 'id');
    }

    public function telegram_chat(): BelongsTo
    {
        return $this->belongsTo(BotUser::class, 'chat_id', 'chat_id');
    }

    public function telegram_user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function run_status_name(): BelongsTo
    {
        return $this->belongsTo(Boolean::class, 'run_status', 'id');
    }

    public function unban_status_name(): BelongsTo
    {
        return $this->belongsTo(Boolean::class, 'unban_status', 'id');
    }

}
