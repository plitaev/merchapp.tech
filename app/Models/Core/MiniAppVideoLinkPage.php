<?php
namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;


class MiniAppVideoLinkPage extends Model
{
    protected $fillable = [
        'mini_app_page_id',
        'mini_app_video_id',
        'pos'
    ];

    public function miniapp(): HasOneThrough {
        return $this->hasOneThrough(MiniApp::class, MiniAppPage::class, 'id', 'id', 'mini_app_page_id', 'mini_app_id');
    }

    public function miniapp_page(): BelongsTo
    {
        return $this->belongsTo(MiniAppPage::class, 'mini_app_page_id', 'id');
    }

    public function miniapp_video(): BelongsTo
    {
        return $this->belongsTo(MiniAppVideo::class, 'mini_app_video_id', 'id');
    }

}
