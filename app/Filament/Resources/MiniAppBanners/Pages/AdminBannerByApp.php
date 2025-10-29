<?php

namespace App\Filament\Resources\MiniAppBanners\Pages;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\MiniAppBanners\MiniAppBannerResource;
use App\Models\Core\MiniAppBannerLinkPage;
use App\Models\Core\MiniAppPage;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class AdminBannerByApp extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = MiniAppBannerResource::class;

    protected string $view = 'filament.resources.mini-app-banner-resource.pages.admin-banner-by-app';
    public static ?string $navigationLabel = "";
    public static ?string $title = "";


    public int $mini_app_id;

    public string $mini_app_name;

    public function mount(int $mini_app_id, string $mini_app_name): void
    {
        $this->mini_app_id = $mini_app_id;
        $this->mini_app_name = rawurldecode($mini_app_name);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(MiniAppBannerLinkPage::with('miniapp_banner')->whereIn('mini_app_page_id', MiniAppPage::select('id')->where('mini_app_id', $this->mini_app_id)->pluck('id')->toArray()))
            ->persistSearchInSession()
            ->columns([
                TextColumn::make('miniapp_page.name')
                    ->label('Страница')
                    ->searchable(),
                TextColumn::make('miniapp_banner_class.name')
                    ->label('Тип')
                    ->searchable(),
                TextColumn::make('miniapp_banner.name')
                    ->label('Название баннера')
                    ->searchable(),
                ImageColumn::make('miniapp_banner.image')
                    ->disk('local')
                    ->label('Изображение')
                    ->url(fn(MiniAppBannerLinkPage $record) => env('APP_URL').'/content/'.$record->image)
                    ->openUrlInNewTab()
                    ->searchable(),
                TextColumn::make('miniapp_banner.button_url')
                    ->label('URL на кнопке')
                    ->url(fn(MiniAppBannerLinkPage $record) => $record->button_url)
                    ->openUrlInNewTab()
                    ->color('primary')
                    ->searchable(),
                TextColumn::make('miniapp_banner.button_text')
                    ->label('Текст на кнопке')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()->url(fn($record) => "/admin/mini-app-banners/".$record->mini_app_page_id."/".$record->mini_app_banner_id."/admin"),
                DeleteAction::make(),
            ])
            ->recordUrl(fn($record) => "/admin/mini-app-banners/".$record->mini_app_page_id."/".$record->mini_app_banner_id."/admin")
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

}
