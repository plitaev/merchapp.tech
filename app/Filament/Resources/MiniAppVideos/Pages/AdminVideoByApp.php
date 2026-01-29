<?php

namespace App\Filament\Resources\MiniAppVideos\Pages;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use App\Filament\Resources\MiniAppBanners\MiniAppBannerResource;
use App\Models\Core\MiniAppBannerLinkPage;
use App\Models\Core\MiniAppPage;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class AdminVideoByApp extends Page implements HasTable
{
    use InteractsWithTable;

    //protected static string $resource = MiniAppVideoResource::class;

    protected string $view = 'filament.resources.mini-app-banner-resource.pages.admin-banner-by-app';
    public static ?string $navigationLabel = "";
    public static ?string $title = "";


    public int $mini_app_id;

    public string $mini_app_name;

    public function mount(int $mini_app_id, string $mini_app_name): void
    {
        if (auth()->user()->hasPermissionTo('Update:MiniAppBanner')) {

            $this->mini_app_id = $mini_app_id;
            $this->mini_app_name = rawurldecode($mini_app_name);

        }else{
            redirect('/admin/bots/access');
        }
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(MiniAppBannerLinkPage::with('miniapp_banner')->whereIn('mini_app_page_id', MiniAppPage::select('id')->where('mini_app_id', $this->mini_app_id)->pluck('id')->toArray()))
            ->persistSearchInSession()
            ->columns([
                TextColumn::make('miniapp_page.name')
                    ->label('Страница')
                    ->disabled(auth()->user()->hasPermissionTo('Update:MiniAppPage')?false:true)
                    ->searchable(),
                TextColumn::make('miniapp_banner_class.name')
                    ->label('Тип')
                    ->disabled(auth()->user()->hasPermissionTo('Update:MiniAppPage')?false:true)
                    ->searchable(),
                TextColumn::make('miniapp_banner.name')
                    ->label('Название баннера')
                    ->disabled(auth()->user()->hasPermissionTo('Update:MiniAppPage')?false:true)
                    ->searchable(),
                ImageColumn::make('miniapp_banner.image')
                    ->disk('local')
                    ->label('Изображение')
                    ->disabled(auth()->user()->hasPermissionTo('Update:MiniAppPage')?false:true)
                    ->url(fn(MiniAppBannerLinkPage $record) => env('APP_URL').'/content/'.$record->image)
                    ->openUrlInNewTab()
                    ->searchable(),
                TextColumn::make('miniapp_banner.button_url')
                    ->label('URL на кнопке')
                    ->url(fn(MiniAppBannerLinkPage $record) => $record->button_url)
                    ->disabled(auth()->user()->hasPermissionTo('Update:MiniAppPage')?false:true)
                    ->openUrlInNewTab()
                    ->color('primary')
                    ->searchable(),
                TextColumn::make('miniapp_banner.button_text')
                    ->disabled(auth()->user()->hasPermissionTo('Update:MiniAppPage')?false:true)
                    ->label('Текст на кнопке')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()->url(fn($record) => "/admin/mini-app-banners/".$record->mini_app_page_id."/".$record->mini_app_banner_id."/admin")
                    ->visible(!auth()->user()->can('Create:MiniAppPage')),
                EditAction::make()->url(fn($record) => "/admin/mini-app-banners/".$record->mini_app_page_id."/".$record->mini_app_banner_id."/admin")
                    ->visible(auth()->user()->can('Update:MiniAppPage')),
                DeleteAction::make()
                    ->visible(auth()->user()->can('Delete:MiniAppPage')),

            ])
            ->recordUrl(fn($record) => "/admin/mini-app-banners/".$record->mini_app_page_id."/".$record->mini_app_banner_id."/admin")
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

}
