<?php
namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BotUserLinkMiniAppVideo extends Model
{
    protected $table='bot_user_link_miniapp_videos';

    protected $fillable = [
        'id',
        'bot_user_id',
        'mini_app_video_id'
    ];

    public function bot_user(): BelongsTo {
        return $this->belongsTo(BotUser::class, 'bot_user_id', 'id');
    }

    public function miniapp_video(): BelongsTo
    {
        return $this->belongsTo(MiniAppVideo::class, 'mini_app_video_id', 'id');
    }

}
