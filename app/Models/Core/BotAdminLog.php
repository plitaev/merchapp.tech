<?php
namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BotAdminLog extends Model
{
    use HasFactory;

    protected $table='bot_admin_log';

    protected $fillable = [
        'user_id',
        'bot_user_id',
        'name',
    ];

    public function bot_users(): BelongsTo
    {
        return $this->belongsTo(BotUser::class);
    }

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
