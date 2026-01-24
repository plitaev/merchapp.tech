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

use Filament\Forms\Components\Select;

class AdminMiniAppPage extends Page implements HasTable, HasForms
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static string $resource = MiniAppPageResource::class;

    protected string $view = 'filament.resources.mini-app-page-resource.pages.admin-mini-app-page';

    protected static ?string $model = MiniAppPage::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    public ?array $data = [];

    public string $name;

    public $record;

    public function getRecord(): ?Model
    {
        return MiniAppPage::class;
    }

    public function getTitle(): string|Htmlable
    {
        return $this->name;
    }

    public function getHeading(): string
    {
        return $this->name;
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function mount(int $record): void
    {

        $data = ($record>0?MiniAppPage::with('miniapp')->find($record)->toArray():[]);
        if ($record == 0) $data['url'] = hash('sha256', time());
        $this->name = ($record > 0?$data['name']:'Новая страница');

        $this->form->fill($data);

        if (!auth()->user()->hasPermissionTo('View:MiniAppPage')) {
            redirect('/admin/bots/access');
        }
    }

    protected function getForms(): array
    {
        return ['form'];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Мини-приложение')
                    ->schema([
                        Select::make('mini_app_id')
                            ->label('Страница опубликована в приложении')
                            ->required()
                            ->options(MiniApp::all()->pluck('name', 'id'))
                            ->searchable(),
                    ]),
                Section::make('Название страницы')
                    ->description('Название страницы используется только для администраторов. Оно не отображается в мини-приложении.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Введите название в это поле')
                            ->required()
                            ->validationMessages([
                                'required' => 'Обязательно укажите название страницы',
                            ]),
                    ]),
                Section::make('Ссылка на страницу')
                    ->description('Введите URL, по которому будет открываться данная страница в приложении')
                    ->schema([
                        TextInput::make('url')
                            ->label('Не изменять')
                            ->required()
                            ->validationMessages([
                                'required' => 'Обязательно укажите ссылку на страницу',
                            ]),
                    ]),
                Actions::make([
                    Action::make('Сохранить')
                        ->action(function () {
                            $data = $this->form->getState();

                            Notification::make()
                                ->title("Данные успешно сохранены")
                                ->success()
                                ->send();

                            if ($this->record > 0) {
                                MiniAppPage::where('id', $this->record)->update($data);
                                return redirect('/admin/mini-app-pages/'.$this->record.'/admin');
                            } else {
                                $new = MiniAppPage::create($data);
                                return redirect('/admin/mini-app-pages/'.$new->id.'/admin');
                            }

                        })
                        ->visible(auth()->user()->hasPermissionTo('Create:MiniAppPage')),

                    Action::make('Cancel')
                        ->action(function () {
                            return redirect('/admin/mini-app-pages');
                        })
                        ->label('Отменить и вернуться назад')
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
                EditAction::make()->url(fn($record) => "/admin/mini-app-banners/".$this->record."/".$record->mini_app_banner_id."/admin"),
                DeleteAction::make()
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
