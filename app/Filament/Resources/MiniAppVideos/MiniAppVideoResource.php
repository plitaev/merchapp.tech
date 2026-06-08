<?php

namespace App\Filament\Resources\MiniAppVideos;
use App\Filament\Resources\MiniAppVideos\Pages\AdminMiniAppVideo;
use App\Filament\Resources\MiniAppVideos\Pages\AdminMiniAppVideoLinkPages;
use App\Filament\Resources\MiniAppVideos\Pages\AdminVideoByApp;
use App\Filament\Resources\MiniAppVideos\Pages\AdminMiniAppVideoTimePoint;

use App\Filament\Resources\MiniAppVideos\Pages\CreateMiniAppVideo;
use App\Filament\Resources\MiniAppVideos\Pages\EditMiniAppVideo;
use App\Filament\Resources\MiniAppVideos\Pages\ListMiniAppVideos;
use App\Filament\Resources\MiniAppVideos\Schemas\MiniAppVideoForm;
use App\Filament\Resources\MiniAppVideos\Tables\MiniAppVideosTable;
use App\Models\Core\MiniAppVideo;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MiniAppVideoResource extends Resource
{
    protected static ?string $model = MiniAppVideo::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Video';

    public static function form(Schema $schema): Schema
    {
        return MiniAppVideoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MiniAppVideosTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => AdminMiniAppVideo::route('/'),
            'create' => AdminMiniAppVideo::route('/{id}/admin'),
            'edit' => AdminMiniAppVideo::route('/{id}/admin'),
            //'admin' => AdminMiniAppVideo::route('/{mini_app_page_id}/{id}/{mini_app_page_block_id}/admin'),
            'admin' => AdminMiniAppVideo::route('/{mini_app_page_id}/{id}/admin'),
            'admin_video_by_app' => AdminVideoByApp::route('/{mini_app_id}/{mini_app_name}/admin_video_by_app'),
            'admin_mini_app_video_time_point' => AdminMiniAppVideoTimePoint::route('/{mini_app_page_id}/{mini_app_video_id}/{id}/admin-mini-app-video-time-point'),
            'admin_mini_app_video_link_pages' => AdminMiniAppVideoLinkPages::route('/{mini_app_page_id}/{mini_app_video_id}/admin_mini_app_video_link_pages'),
        ];
    }
}

