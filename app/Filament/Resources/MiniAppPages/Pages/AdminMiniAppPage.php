<?php
namespace App\Filament\Resources\MiniAppPages\Pages;

use App\Models\Core\MiniApp;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use App\Actions\Core\MiniAppBanner\MiniAppBannerGetLinkForRecord;
use App\Filament\Resources\MiniAppPages\MiniAppPageResource;
use App\Models\Core\MiniAppBanner;
use App\Models\Core\MiniAppBannerLinkPage;
use App\Models\Core\MiniAppPage;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class AdminMiniAppPage extends Page implements HasTable, HasForms
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static string $resource = MiniAppPageResource::class;

    protected string $view = 'filament.resources.mini-app-page-resource.pages.admin-mini-app-page';

    protected static ?string $model = MiniAppPage::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    public ?array $data = [];

    public $record;

    public function getRecord(): ?Model
    {
        return MiniAppPage::class;
    }

    public function getTitle(): string|Htmlable
    {
        return __('Настройки страницы мини-приложения');
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function mount(int $record): void
    {
        $data = ($record>0?MiniAppPage::with('miniapp')->find($record)->toArray():[]);
        if ($record == 0) $data['url'] = hash('sha256', time());
        $this->form->fill($data);
    }

    protected function getForms(): array
    {
        return ['form'];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Настройки страницы мини-приложения')
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ])
                    ->schema([
                    Select::make('mini_app_id')
                        ->label('Название приложения')
                        ->required()
                        ->disabled(auth()->user()->hasPermissionTo('Update:MiniAppPage')?false:true)
                        ->options(MiniApp::all()->pluck('name', 'id'))
                        ->searchable(),
                    TextInput::make('name')
                        ->label('Название страницы')
                        ->disabled(auth()->user()->hasPermissionTo('Update:MiniAppPage')?false:true)
                        ->maxLength(255),
                    TextInput::make('url')
                        ->label('Ссылка')
                        ->disabled(auth()->user()->hasPermissionTo('Update:MiniAppPage')?false:true)
                        ->required()
                        ->maxLength(255)
                ]),
               Actions::make([
                   Action::make('Сохранить')
                       ->action(function () {
                       $data = $this->form->getState();


                       if ($this->id>0) {
                           MiniAppPage::where('id', $this->id)->update($data);
                           $output_id = $this->id;
                       } else {
                           $new = MiniAppPage::create($data);
                           $output_id = $new->id;
                       }

                       Notification::make()
                           ->title('Данные успешно сохранены!')
                           ->success()
                           ->send();

                       return redirect('/admin/mini-app-pages');
                   })
                   ->visible(auth()->user()->hasPermissionTo('Create:MiniAppPage')),
                ])
            ])->statePath('data');

    }


    public function table(Table $table): Table
    {
        $miniAppBannerGetLinkForRecord = new MiniAppBannerGetLinkForRecord();

        return $table
            ->query(MiniAppBannerLinkPage::query()->with('miniapp_banner')->where('mini_app_page_id', $this->record))
            ->persistSearchInSession()
            ->columns([
                TextColumn::make('pos')
                    ->label('№')
                    ->searchable(),
                TextColumn::make('miniapp_banner_class.name')
                    ->label('Тип')
                    ->searchable(),
                TextColumn::make('miniapp_banner.name')
                    ->label('Название')
                    ->searchable(),
                ImageColumn::make('miniapp_banner.image')
                    ->disk('local')
                    ->label('Изображение')
                    ->url(fn(MiniAppBannerLinkPage $record) => env('APP_URL').'/content/'.$record->miniapp_banner->image)
                    ->openUrlInNewTab()
                    ->searchable(),
                TextColumn::make('button_url')
                    ->label('Ссылка на кнопке')
                    ->url(fn(MiniAppBannerLinkPage $record) => $miniAppBannerGetLinkForRecord->handle($record))
                    ->getStateUsing('Открыть')
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
                ViewAction::make()->url(fn($record) => "/admin/mini-app-banners/".$this->record."/".$record->mini_app_banner_id."/admin")
                    ->visible(!auth()->user()->can('Create:MiniAppBannerLinkPage')),
                EditAction::make()->url(fn($record) => "/admin/mini-app-banners/".$this->record."/".$record->mini_app_banner_id."/admin")
                    ->visible(auth()->user()->can('Create:MiniAppBannerLinkPage')),
                DeleteAction::make()
                    ->visible(auth()->user()->can('Delete:MiniAppBannerLinkPage'))
            ->after(function (MiniAppBannerLinkPage $record) {
                        $check = MiniAppBannerLinkPage::where('mini_app_banner_id', $record->mini_app_banner_id)->count();
                        if ($check == 0) MiniAppBanner::destroy($record->mini_app_banner_id);
                    })
            ])
            ->recordUrl(fn($record) => "/admin/mini-app-banners/".$this->record."/".$record->mini_app_banner_id."/admin")
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->after(function (MiniAppBannerLinkPage $record, Collection $selectedRecords) {
                            foreach ($selectedRecords as $selectedRecord) {
                                $check = MiniAppBannerLinkPage::where('mini_app_banner_id', $selectedRecord->mini_app_banner_id)->count();
                                if ($check == 0) MiniAppBanner::destroy($selectedRecord->mini_app_banner_id);
                            }
                        }),
                ])
            ])
            ->defaultSort('pos')
            ->reorderable('pos');
    }
}
