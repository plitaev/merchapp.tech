<?php
namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

use App\Models\Core\BotMessage;
use App\Models\Core\BotUser;
use App\Models\Core\RunStatus;
use App\Models\Core\Sending;

class TelegramSendMessageSchedule extends Model
{
    protected $fillable = [
        'sending_id',
        'bot_user_id',
        'run_status',
        'message_id'
    ];

    public function bot_user(): BelongsTo
    {
        return $this->belongsTo(BotUser::class);
    }

    public function sending(): BelongsTo
    {
        return $this->belongsTo(Sending::class);
    }

    public function run_status_name(): BelongsTo
    {
        return $this->belongsTo(RunStatus::class);
    }

    public function bot_message(): HasOneThrough {
        return $this->hasOneThrough(BotMessage::class, Sending::class, 'id', 'id', 'sending_id', 'bot_message_id')->with('bot_message_appointment');
    }

}
