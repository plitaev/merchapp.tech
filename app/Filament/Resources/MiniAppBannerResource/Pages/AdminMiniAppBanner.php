<?php
namespace App\Filament\Resources\MiniAppBannerResource\Pages;

use App\Actions\Core\MiniAppBanner\MiniAppBannerBuildPosList;
use App\Actions\Core\MiniAppBanner\MiniAppBannerSave;
use App\Filament\Resources\MiniAppPageResource;
use App\Models\Core\MiniAppBanner;
use App\Models\Core\MiniAppBannerClass;
use App\Models\Core\MiniAppBannerLinkPage;
use App\Models\Core\MiniAppPage;
use Filament\Forms;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AdminMiniAppBanner extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static string $resource = MiniAppPageResource::class;

    protected static string $view = 'filament.resources.mini-app-banner-resource.pages.admin-mini-app-banner';

    protected static ?string $model = MiniAppPage::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected ?string $subheading = 'Оформление баннера и контент по клику';

    public ?array $data = [];
    public ?array $data_banner_link_page = [];

    public int $mini_app_page_id;

    public int $banner_id;

    public $pos_list;

    public function getRecord(): ?Model
    {
        return MiniAppPage::class;
    }

    public function getTitle(): string|Htmlable
    {
        return __('Настройки баннера');
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function mount(int $mini_app_page_id, int $banner_id): void
    {
        $this->mini_app_page_id = $mini_app_page_id;
        $this->banner_id = $banner_id;

        $this->pos_list = (new MiniAppBannerBuildPosList())->handle($mini_app_page_id, $banner_id);

        $data = ($banner_id>0?MiniAppBanner::find($banner_id)->toArray():['id' => 0, 'mini_app_page_id' => $mini_app_page_id, 'pos' => $this->pos_list[1]]);
        $this->form->fill($data);

        $this->form_banner_link_page->fill([]);
    }

    protected function getForms(): array
    {
        return ['form', 'form_banner_link_page'];
    }

    public function form(Form $form): Form
    {
        $A = [];

        if ($this->banner_id==0) {
            $A[] = Select::make('mini_app_page_id')
                ->label('Страница')
                ->required()
                ->options(MiniAppPage::all()->pluck('name', 'id'))
                ->searchable()
                ->columns(['sm' => 2, 'xl' => 2, '2xl' => 2]);
        }

        $A[] = Forms\Components\Select::make('banner_class_id')
            ->label('Тип баннера')
            ->required()
            ->options(MiniAppBannerClass::all()->pluck('name', 'id'))
            ->searchable()
            ->columns(['sm' => 2, 'xl' => 2, '2xl' => 2]);

        $A[] = Forms\Components\TextInput::make('name')
            ->label('Название баннера')
            ->required()
            ->maxLength(255)
            ->columns(['sm' => 2, 'xl' => 2, '2xl' => 2]);

        if ($this->banner_id==0) {
            $A[] = Forms\Components\Select::make('pos')
                ->label('Порядковый номер')
                ->required()
                ->options($this->pos_list[0])
                ->searchable()
                ->columns(['sm' => 2, 'xl' => 2, '2xl' => 2]);
        }

        return $form
            ->schema([
                Section::make('Основные настройки')
                    ->description('На какой странице отображается баннер и как он выглядит')
                    ->columns([
                        'sm' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ])
                    ->schema($A),
                Section::make('Изображение на баннере')
                    ->description('Как пользователь видит этот баннер')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->disk('local')
                            ->directory('miniapp_banners')
                            ->visibility('public')
                    ]),
                Section::make('Контент, открывающийся по клику на баннер')
                    ->description('Только PDF или ссылка. Вставляйте только либо PDF, либо ссылка. Если одновременно загружен PDF и ссылка, то для пользователя будет открываться PDF!')
                    ->schema([
                        Forms\Components\FileUpload::make('button_pdf')
                            ->label('PDF')
                            ->disk('local')
                            ->directory('miniapp_pdf')
                            ->visibility('public'),
                        Forms\Components\TextInput::make('button_url')
                            ->label('Ссылка на кнопке')
                            ->maxLength(255),
                    ]),
                Section::make('Параметры кнопки')
                    ->description('Цвета и надпись')
                    ->columns([
                        'sm' => 2,
                        'xl' => 3,
                        '2xl' => 3,
                    ])
                    ->schema([
                        Forms\Components\TextInput::make('button_text')
                            ->label('Текст на кнопке')
                            ->required()
                            ->maxLength(255)
                            ->columns([
                                'sm' => 2,
                                'xl' => 3,
                                '2xl' => 3,
                            ]),
                        Forms\Components\ColorPicker::make('button_bg_color')
                            ->label('Цвет фона кнопки')
                            ->required()
                            ->columns([
                                'sm' => 2,
                                'xl' => 3,
                                '2xl' => 3,
                            ]),
                        Forms\Components\ColorPicker::make('button_text_color')
                            ->label('Цвет текста на кнопке')
                            ->required()
                            ->columns([
                                'sm' => 2,
                                'xl' => 3,
                                '2xl' => 3,
                            ]),
                        Hidden::make('id')
                            ->label('id')
                            ->required(),
                    Actions::make([
                        Action::make('Сохранить')
                            ->action(function (Request $request) {
                                (new MiniAppBannerSave())->handle($this->form->getState());

                                Notification::make()
                                    ->title('Данные успешно сохранены!')
                                    ->success()
                                    ->send();

                                redirect("/admin/mini-app-pages/".$this->mini_app_page_id."/admin");

                            }),
                    ])
                    ])
            ])->statePath('data');
    }

    public function form_banner_link_page(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Выберите страницу')
                    ->description('Где должен отображаться баннер')
                    ->schema([
                        Select::make('mini_app_page_id')
                            ->label('Страница')
                            ->required()
                            ->options(
                                MiniAppPage::query()->pluck('name', 'id')
                            )
                            ->searchable()
                    ]),
                Actions::make([
                    Action::make('Сохранить')
                        ->action(function () {
                            $formdata = $this->form_banner_link_page->getState();

                            $last_pos = MiniAppBannerLinkPage::select('pos')->where('mini_app_page_id', $formdata['mini_app_page_id'])->orderByDesc('pos')->first();
                            if ($last_pos) {
                                $pos = $last_pos->pos + 1;
                            } else {
                                $pos = 1;
                            }

                            MiniAppBannerLinkPage::upsert(
                                ['mini_app_banner_id' => $this->banner_id, 'mini_app_page_id' => $formdata['mini_app_page_id'], 'pos' => $pos],
                                ['mini_app_page_id', 'mini_app_banner_id'],
                                ['updated_at' => now()]
                            );

                            $this->dispatch('close-modal', id: 'add-page-modal');
                        }),
                    Action::make('Отмена')
                        ->action(function () {
                            $this->dispatch('close-modal', id: 'add-page-modal');
                        })
                ])
            ])->statePath('data_banner_link_page');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(MiniAppBannerLinkPage::query()->with('miniapp_page')->where('mini_app_banner_id', $this->banner_id))
            ->columns([
                Tables\Columns\TextColumn::make('miniapp_page.name')
                    ->label('Страница')
                    ->searchable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->recordUrl(fn($record) => "/admin/mini-app-pages/".$this->mini_app_page_id."/admin");
    }

}
