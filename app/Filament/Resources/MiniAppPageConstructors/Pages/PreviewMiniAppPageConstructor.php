<?php
namespace App\Filament\Resources\MiniAppPageConstructors\Pages;

use App\Filament\Resources\MiniAppPageConstructors\MiniAppPageConstructorResource;
use App\Models\Core\MiniAppPage;
use Filament\Resources\Pages\Page;

class PreviewMiniAppPageConstructor extends Page
{
    protected static string $resource = MiniAppPageConstructorResource::class;

    protected string $view = 'filament.resources.mini-app-page-preview.pages.look-mini-app-page';

    protected static ?string $model = MiniAppPage::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

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
