<?php

namespace App\Filament\Resources\MiniAppVideos\Pages;

use App\Filament\Resources\MiniAppVideos\MiniAppVideoResource;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;

use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

use App\Models\Core\MiniAppVideoLinkPage;
use App\Models\Core\MiniAppPage;

class AdminVideoByApp extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = MiniAppVideoResource::class;

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
            ->query(MiniAppVideoLinkPage::with('miniapp_video')->with('miniapp_page')->whereIn('mini_app_page_id', MiniAppPage::select('id')->where('mini_app_id', $this->mini_app_id)->pluck('id')->toArray()))
            ->persistSearchInSession()
            ->columns([
                TextColumn::make('miniapp_page.name')
                    ->label('Страница')
                    ->disabled(auth()->user()->hasPermissionTo('Update:MiniAppPage')?false:true)
                    ->searchable(),
                TextColumn::make('miniapp_video.name')
                    ->label('Видео')
                    ->disabled(auth()->user()->hasPermissionTo('Update:MiniAppPage')?false:true)
                    ->searchable(),
                ImageColumn::make('miniapp_video.image')
                    ->disk('local')
                    ->label('Изображение')
                    ->url(fn(MiniAppVideoLinkPage $record) => env('APP_URL').'/content/'.$record->miniapp_video->image)
                    ->openUrlInNewTab()
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
            ->recordUrl(fn($record) => "/admin/mini-app-videos/".$record->mini_app_page_id."/".$record->mini_app_video_id."/admin")
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

}
