<?php
namespace App\Filament\Resources\MiniAppPageResource\Pages;

use App\Filament\Resources\MiniAppPageResource;
use App\Models\Core\MiniAppPage;
use Filament\Resources\Pages\Page;

class PreviewMiniAppPage extends Page
{
    protected static string $resource = MiniAppPageResource::class;

    protected static string $view = 'filament.resources.mini-app-page-preview.pages.look-mini-app-page';

    protected static ?string $model = MiniAppPage::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static ?string $label = "Предварительный просмотр";
    public static ?string $navigationLabel = "Предварительный просмотр";
    public static ?string $title = "Предварительный просмотр";

    public int $mini_app_page_id;
    public string $mini_app_page_url;

    public function getTitle() : string
    {
        return '';
    }

    public function mount(int $mini_app_page_id): void
    {
        $page = MiniAppPage::select('url')->find($mini_app_page_id);

        $this->mini_app_page_id = $mini_app_page_id;
        $this->mini_app_page_url = $page->url;
    }
}
