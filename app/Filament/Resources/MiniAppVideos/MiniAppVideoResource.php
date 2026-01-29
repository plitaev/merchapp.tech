<?php

namespace App\Filament\Resources\MiniAppVideos;

use App\Filament\Resources\MiniAppVideos\Pages\AdminMiniAppVideo;
use App\Filament\Resources\MiniAppVideos\Pages\AdminVideoByApp;

use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

use Filament\Forms\Components\FileUpload;
use Filament\Resources\Resource;

use Filament\Tables;
use Filament\Tables\Table;

use Filament\Schemas\Schema;

use App\Models\Core\FunnelCondition;

class MiniAppVideoResource extends Resource
{
    protected static ?string $model = FunnelCondition::class;

    public static ?string $label = "Видео";
    public static ?string $navigationLabel = "Видео";
    public static ?string $title = "Видео";

    public static function getPluralLabel(): ?string {return "Видео";}

    protected static bool $shouldRegisterNavigation = false;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
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
            'create' => AdminMiniAppVideo::route('/{id}/admin'),
            'edit' => AdminMiniAppVideo::route('/{id}/admin'),
            'admin' => AdminMiniAppVideo::route('/{mini_app_page_id}/{id}/admin'),
            'admin_video_by_app' => AdminVideoByApp::route('/{mini_app_id}/{mini_app_name}/admin_video_by_app'),
        ];
    }
}
