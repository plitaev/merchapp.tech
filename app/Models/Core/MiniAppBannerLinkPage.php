<?php
namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;


class MiniAppBannerLinkPage extends Model
{
    protected $fillable = [
        'mini_app_page_id',
        'mini_app_banner_id',
        'pos'
    ];

    public function miniapp_page(): BelongsTo
    {
        return $this->belongsTo(MiniAppPage::class, 'mini_app_page_id', 'id');
    }

    public function miniapp_banner(): BelongsTo
    {
        return $this->belongsTo(MiniAppBanner::class, 'mini_app_banner_id', 'id');
    }

    public function miniapp_banner_class(): HasOneThrough {
        return $this->hasOneThrough(MiniAppBannerClass::class, MiniAppBanner::class, 'id', 'id', 'mini_app_banner_id', 'banner_class_id');
    }

}
