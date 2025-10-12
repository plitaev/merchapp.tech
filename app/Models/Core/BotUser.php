<?php
namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BotUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'telegram_chat_id',
        'bot_id',
        'bot_branch_id',
        'first_name',
        'last_name',
        'hand_name',
        'eighteen',
        'username',
        'email',
        'business_bot_account',
        'language_code',
        'ban',
        'ban_time',
        'unban',
        'unban_time',
        'listen_email_status',
        'listen_email_status_timestamp',
        'listen_handname_status',
        'listen_handname_status_timestamp',
        'listen_check_access_status',
        'listen_check_access_status_timestamp',
        'listen_email_pay_not_found_first_status',
        'listen_email_pay_not_found_first_status_timestamp',
        'date_end',
        'sys_welcome_message_status',
        'sys_welcome_message_status_timestamp',
        'sys_go_to_pay_status',
        'sys_go_to_pay_status_timestamp',
        'sys_email_pay_not_found_first_status',
        'sys_email_pay_not_found_first_status_timestamp',
        'listen_success_message_status',
        'listen_success_message_status_timestamp',
        'time_zone_name',
        'run_status'
    ];



    public function user_email(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class);
    }

    public function bot_message(): BelongsTo
    {
        return $this->belongsTo(BotMessage::class);
    }

    public function users(): BelongsTo
    {
        return $this->belongsTo(BotUser::class, 'username', 'username');
    }

    public function ban_name(): BelongsTo
    {
        return $this->belongsTo(Boolean::class, 'ban', 'id');
    }

    public function unban_name(): BelongsTo
    {
        return $this->belongsTo(Boolean::class, 'unban', 'id');
    }
}
