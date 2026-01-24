<?php

namespace App\Filament\Resources\MiniAppVideos;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ColorPicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\MiniAppBanners\Pages\ListMiniAppBanners;
use App\Filament\Resources\MiniAppBanners\Pages\CreateMiniAppBanner;
use App\Filament\Resources\MiniAppBanners\Pages\EditMiniAppBanner;
use App\Filament\Resources\MiniAppBanners\Pages\AdminMiniAppBanner;
use App\Filament\Resources\MiniAppBanners\Pages\AdminBannerByApp;
use App\Filament\Resources\MiniAppBannerResource\Pages;
use App\Filament\Resources\MiniAppBannerResource\RelationManagers;
use App\Models\Core\MiniAppBanner;
use App\Models\Core\MiniAppBannerClass;
use App\Models\Core\MiniAppPage;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MiniAppVideoResource extends Resource
{
    protected static ?string $model = MiniAppVideo::class;

    public static ?string $label = "Видео";
    public static ?string $navigationLabel = "Видео";
    public static ?string $title = "Видео";

    protected static bool $shouldRegisterNavigation = false;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static ?int $navigationSort = 4;

    public static function getPluralLabel(): ?string {return "Видео";}

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('mini_app_page_id')
                    ->label('Страница')
                    ->required()
                    ->options(MiniAppPage::all()->pluck('name', 'id'))
                    ->searchable(),
                Select::make('banner_class_id')
                    ->label('Тип баннера')
                    ->required()
                    ->options(MiniAppBannerClass::all()->pluck('name', 'id'))
                    ->searchable(),
                FileUpload::make('image')
                    ->label('Изображение на баннере')
                    ->disk('local')
                    ->directory('miniapp_banners')
                    ->visibility('public'),
                FileUpload::make('button_pdf')
                    ->label('PDF')
                    ->disk('local')
                    ->directory('miniapp_pdf')
                    ->visibility('public'),
                TextInput::make('button_url')
                    ->label('Ссылка на кнопке')
                    ->maxLength(255),
                TextInput::make('button_text')
                    ->label('Текст на кнопке')
                    ->required()
                    ->maxLength(255),
                ColorPicker::make('button_bg_color')
                    ->label('Цвет фона кнопки')
                    ->required(),
                ColorPicker::make('button_text_color')
                    ->label('Цвет текста на кнопке')
                    ->required(),
                TextInput::make('name')
                    ->label('Название баннера')
                    ->maxLength(255),
                TextInput::make('pos')
                    ->label('Порядковый номер')
                    ->required()
                    ->maxLength(255),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('miniapp_through_page.name')
                    ->label('Приложение')
                    ->searchable(),
                TextColumn::make('miniapp_page.name')
                    ->label('Страница')
                    ->searchable(),
                TextColumn::make('miniapp_banner_class.name')
                    ->label('Тип')
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Название баннера')
                    ->searchable(),
                ImageColumn::make('image')
                    ->disk('local')
                    ->label('Изображение')
                    ->url(fn(MiniAppBanner $record) => env('APP_URL').'/content/'.$record->image)
                    ->openUrlInNewTab()
                    ->searchable(),
                TextColumn::make('button_url')
                    ->label('URL на кнопке')
                    ->url(fn(MiniAppBanner $record) => $record->button_url)
                    ->openUrlInNewTab()
                    ->color('primary')
                    ->searchable(),
                TextColumn::make('button_text')
                    ->label('Текст на кнопке')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
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
            'index' => ListMiniAppBanners::route('/'),
            'create' => CreateMiniAppBanner::route('/create'),
            'edit' => EditMiniAppBanner::route('/{record}/edit'),
            'admin' => AdminMiniAppBanner::route('/{mini_app_page_id}/{banner_id}/admin'),
            'admin_banner_by_app' => AdminBannerByApp::route('/{mini_app_id}/{mini_app_name}/admin_banner_by_app'),
        ];
    }
}
