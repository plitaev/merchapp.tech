<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MiniAppBannerResource\Pages;
use App\Filament\Resources\MiniAppBannerResource\RelationManagers;
use App\Models\Core\MiniAppBanner;
use App\Models\Core\MiniAppBannerClass;
use App\Models\Core\MiniAppPage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MiniAppBannerResource extends Resource
{
    protected static ?string $model = MiniAppBanner::class;

    public static ?string $label = "Баннер";
    public static ?string $navigationLabel = "Баннеры";
    public static ?string $title = "Баннеры";

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static ?int $navigationSort = 4;

    public static function getPluralLabel(): ?string {return "Баннеры";}

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('mini_app_page_id')
                    ->label('Страница')
                    ->required()
                    ->options(MiniAppPage::all()->pluck('name', 'id'))
                    ->searchable(),
                Forms\Components\Select::make('banner_class_id')
                    ->label('Тип баннера')
                    ->required()
                    ->options(MiniAppBannerClass::all()->pluck('name', 'id'))
                    ->searchable(),
                Forms\Components\FileUpload::make('image')
                    ->label('Изображение на баннере')
                    ->disk('local')
                    ->directory('miniapp_banners')
                    ->visibility('public'),
                Forms\Components\FileUpload::make('button_pdf')
                    ->label('PDF')
                    ->disk('local')
                    ->directory('miniapp_pdf')
                    ->visibility('public'),
                Forms\Components\TextInput::make('button_url')
                    ->label('Ссылка на кнопке')
                    ->maxLength(255),
                Forms\Components\TextInput::make('button_text')
                    ->label('Текст на кнопке')
                    ->required()
                    ->maxLength(255),
                Forms\Components\ColorPicker::make('button_bg_color')
                    ->label('Цвет фона кнопки')
                    ->required(),
                Forms\Components\ColorPicker::make('button_text_color')
                    ->label('Цвет текста на кнопке')
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->label('Название баннера')
                    ->maxLength(255),
                Forms\Components\TextInput::make('pos')
                    ->label('Порядковый номер')
                    ->required()
                    ->maxLength(255),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('miniapp_through_page.name')
                    ->label('Приложение')
                    ->searchable(),
                Tables\Columns\TextColumn::make('miniapp_page.name')
                    ->label('Страница')
                    ->searchable(),
                Tables\Columns\TextColumn::make('miniapp_banner_class.name')
                    ->label('Тип')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Название баннера')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('image')
                    ->disk('local')
                    ->label('Изображение')
                    ->url(fn(MiniAppBanner $record) => env('APP_URL').'/content/'.$record->image)
                    ->openUrlInNewTab()
                    ->searchable(),
                Tables\Columns\TextColumn::make('button_url')
                    ->label('URL на кнопке')
                    ->url(fn(MiniAppBanner $record) => $record->button_url)
                    ->openUrlInNewTab()
                    ->color('primary')
                    ->searchable(),
                Tables\Columns\TextColumn::make('button_text')
                    ->label('Текст на кнопке')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListMiniAppBanners::route('/'),
            'create' => Pages\CreateMiniAppBanner::route('/create'),
            'edit' => Pages\EditMiniAppBanner::route('/{record}/edit'),
            'admin' => Pages\AdminMiniAppBanner::route('/{mini_app_page_id}/{banner_id}/admin'),
            'admin_banner_by_app' => Pages\AdminBannerByApp::route('/{mini_app_id}/{mini_app_name}/admin_banner_by_app'),
        ];
    }
}
