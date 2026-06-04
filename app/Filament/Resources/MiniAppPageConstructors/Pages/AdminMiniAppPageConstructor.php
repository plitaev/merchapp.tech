<?php

namespace App\Filament\Resources\MiniAppPageConstructors\Pages;

use App\Filament\Resources\MiniAppPageConstructors\MiniAppPageConstructorResource;

use App\Models\Core\MiniAppBlockType;
use App\Models\Core\MiniAppPageAccess;
use App\Models\Core\MiniAppPageBlock;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Livewire\Features\SupportRedirects\Redirector;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Actions;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Textarea;

use App\Models\Core\BotMessage;
use App\Models\Core\TelegramSendMessageSchedule;
use Illuminate\Support\HtmlString;

use App\Filament\Resources\Bots\BotResource;


use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;

use App\Actions\Core\MiniAppBanner\MiniAppBannerGetLinkForRecord;

use App\Models\Core\MiniApp;
use App\Models\Core\MiniAppBanner;
use App\Models\Core\MiniAppBannerLinkPage;
use App\Models\Core\MiniAppPage;
use App\Models\Core\MiniAppVideo;
use App\Models\Core\MiniAppVideoLinkPage;

class AdminMiniAppPageConstructor extends Page implements HasTable, HasForms,  HasInfolists
{
    use InteractsWithInfolists;
    use InteractsWithForms;
    use InteractsWithTable;

    protected static string $resource = MiniAppPageConstructorResource::class;

    protected string $view = 'filament.resources.mini-app-page-constructor-resource.pages.admin-mini-app-page-constructor';

    protected static ?string $model = MiniAppPage::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    public ?array $data = [];
    public ?array $data_block = [];


    public ?array $form_data = [];

    public int $mini_app_class_id;

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


    protected function getHeaderActions(): array
    {
        return [];
    }

    public function mount(int $record): void
    {

        $data = ($record>0?MiniAppPage::with('miniapp')->find($record)->toArray():[]);
        if ($record == 0) $data['url'] = hash('sha256', time());
        $this->mini_app_class_id = ($record > 0?$data['miniapp']['class_id']:0);
        $this->name = ($record > 0?$data['name']:'Новая страница');

        $this->form->fill($data);
        $this->form_type_block->fill([]);

        if (!auth()->user()->hasPermissionTo('View:MiniAppPage')) {
            redirect('/admin/bots/access');
        }
    }

    public function getHeading(): string
    {
        if ($this->record > 0) {
            return "Редактировать страницу";
        } else {
            return "Добавить страницу";
        }
    }

    protected function getForms(): array
    {
        return ['form','form_type_block'];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Мини-приложение')
                    ->columns([
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ])
                    ->schema([
                        Select::make('mini_app_id')
                            ->label('Страница опубликована в приложении')
                            ->required()
                            ->options(MiniApp::all()->pluck('name', 'id'))
                            ->searchable(),
                        Select::make('mini_app_page_access_id')
                            ->label('Режим доступа')
                            ->options(MiniAppPageAccess::all()->pluck('name', 'id'))
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
                Section::make('Ссылка для кнопки Назад')
                    ->description('Введите URL, на который будет вести кнопка Назад. Если на данной странице кнопка Назад не нужна, оставьте это поле пустым.')
                    ->schema([
                        TextInput::make('back_button_url')
                            ->label('Ссылка')
                    ]),
                Section::make('Перенаправлять на страницу')
                    ->description('Введите ID страницы, на который будет происходить редирект')
                    ->schema([
                        TextInput::make('redirect_to_page')
                            ->label('ID страницы (ссылка)')
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

    public function form_type_block(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make("Блок страницы")
                    ->description('Основные данные')

                    ->schema([
                        Hidden::make('mini_app_page_id'),

                        Select::make('block_type_id')
                            ->label('Тип блока')
                            ->required()
                            ->disabled(auth()->user()->hasPermissionTo('Update:User')?false:true)
                            ->validationMessages([
                                'required' => 'Обязательно выберите значение из списка',
                            ])
                            ->options(
                                MiniAppBlockType::query()->pluck('name', 'id')
                            ),
                        TextInput::make('position')
                            ->label('Позиция')
                            ->maxLength(255),

                    ]),

                Actions::make([
                    Action::make('Сохранить')
                        //->visible(auth()->user()->hasPermissionTo('Update:User'))
                        ->action(function () {
                            $form_data = $this->form_type_block->getState();
                            $form_data['mini_app_page_id'] = $this->record;

                            $new = MiniAppPageBlock::create($form_data);
                            $output_id = $new->record;

                            Notification::make()
                                ->title('Данные успешно сохранены!')
                                ->success()
                                ->send();

                            if ($form_data['block_type_id'] == 1) {
                                return redirect('/admin/mini-app-banners/'.$this->record.'/0/'.$new->id.'/admin');
                            } elseif ($form_data['block_type_id'] == 2) {
                                return redirect('/admin/mini-app-videos/'.$this->record.'/0/'.$new->id.'/admin');
                            }
                        }),
                    Action::make('Отмена')
                        ->action(function () {
                            $this->dispatch('close-modal', id: 'add-page-block');
                        })

                ])
            ])->statePath('data_block');

    }

    public function table(Table $table): Table
    {
        return $table
            ->query(MiniAppPageBlock::query()->with('block_type')->where('mini_app_page_id', $this->record))
            ->columns([
                TextColumn::make('position')
                    ->label('Позиция')
                    ->searchable(),
                TextColumn::make('block_type.name')
                    ->label('Тип блока страницы')
                    ->searchable(),
                TextColumn::make('mini_app_banner.name')
                    ->label('Название Баннера')
                    ->searchable(),
                TextColumn::make('mini_app_video.name')
                    ->label('Название Видео')
                    ->searchable(),
                    //->visible(fn ($record) => $record['block_type.name'] == 'Видео' ? true : false),
            ])
            ->filters([
                //
            ])
            ->recordUrl(fn($record) => "/admin/mini-app-page-constructors/".$this->record."/admin")
            ->recordActions([
                DeleteAction::make()
                    ->before(function ($record) {
//
                        $miniAppPageBlock = MiniAppPageBlock::find($record->id);

                        if($record->block_type_id == 1) {
                            MiniAppBanner::where('mini_app_page_block_id', $miniAppPageBlock->id)->update(['mini_app_page_block_id' => '']);
                        } else if($record->block_type_id == 2) {
                            MiniAppVideo::where('mini_app_page_block_id', $miniAppPageBlock->id)->update(['mini_app_page_block_id' => '']);
                        }
                    })
                    ->visible(auth()->user()->hasPermissionTo('Delete:MiniApp'))

            ]);
    }
}
