<?php
namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class MiniAppBanner extends Model
{
    protected $fillable = [
        'banner_class_id',
        'name',
        'image',
        'button_url',
        'button_pdf',
        'button_text',
        'button_bg_color',
        'button_text_color',
        'mini_app_page_with_video_id',
        'mini_app_page_with_video_show_banner',
        'mini_app_page_block_id'
    ];

    public function miniapp_page(): BelongsTo
    {
        return $this->belongsTo(MiniAppPage::class, 'mini_app_page_id', 'id');
    }

    public function miniapp_banner_class(): BelongsTo
    {
        return $this->belongsTo(MiniAppBannerClass::class, 'banner_class_id', 'id');
    }

    public function miniapp_through_page(): HasOneThrough {
        return $this->hasOneThrough(MiniApp::class, MiniAppPage::class, 'id', 'id', 'mini_app_page_id', 'mini_app_id');
    }
}
