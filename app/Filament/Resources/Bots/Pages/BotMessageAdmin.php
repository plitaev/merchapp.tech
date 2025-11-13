<?php
namespace App\Filament\Resources\Bots\Pages;

use App\Models\Core\BotBranch;
use App\Models\Core\FunnelConditionTrigger;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Actions\Core\BotMessage\BotMessageSave;
use App\Filament\Resources\Bots\BotResource;
use App\Models\Core\Bot;
use App\Models\Core\BotMessage;
use App\Models\Core\BotMessageAppointment;
use App\Models\Core\BotMessageButton;
use App\Models\Core\BotMessageListener;
use App\Models\Core\BotMessageType;
use App\Models\Core\BotUser;
use App\Models\Core\Funnel;
use App\Models\Core\FunnelCondition;
use App\Models\Core\Listener;
use App\Models\Core\User;

use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\RichEditor;

class BotMessageAdmin extends Page implements HasForms, HasTable, HasInfolists
{
    use InteractsWithForms;
    use InteractsWithTable;
    use InteractsWithInfolists;

    protected static string $resource = BotResource::class;

    protected string $view = 'filament.resources.bot-resource.pages.bot-message-admin';

    public int $bot_id;

    public int $id;

    public string $name;
    public string $bot_name;

    public int $bot_message_listener = 0;

    protected static ?string $model = BotMessage::class;

    public static ?string $label = "Сообщение";
    public static ?string $navigationLabel = "Сообщение";
    public static ?string $title = "Сообщение";
    public ?array $data = [];
    public ?array $data_bot_message_link_listener = [];
    public $record;
    public string $send_message_self = "";

    public array $hours = [
        '0' => '0',
        '1' => '1',
        '2' => '2',
        '3' => '3',
        '4' => '4',
        '5' => '5',
        '6' => '6',
        '7' => '7',
        '8' => '8',
        '9' => '9',
        '10' => '10',
        '11' => '11',
        '12' => '12',
        '13' => '13',
        '14' => '14',
        '15' => '15',
        '16' => '16',
        '17' => '17',
        '18' => '18',
        '19' => '19',
        '20' => '20',
        '21' => '21',
        '22' => '22',
        '23' => '23',
        '24' => '24',
        '25' => '25',
        '26' => '26',
        '27' => '27',
        '28' => '28',
        '29' => '29',
        '30' => '30',
        '31' => '31',
        '32' => '32',
        '33' => '33',
        '34' => '34',
        '35' => '35',
        '36' => '36',
        '37' => '37',
        '38' => '38',
        '39' => '39',
        '40' => '40',
        '41' => '41',
        '42' => '42',
        '43' => '43',
        '44' => '44',
        '45' => '45',
        '46' => '46'
    ];

    public array $minutes = [
        '0' => '0',
        '15' => '15',
        '30' => '30',
        '45' => '45'
    ];

    public function getRecord(): ?Model
    {
        return BotMessage::class;
    }

    protected function getHeaderActions(): array
    {
        return [];
    }


    public function mount(int $bot_id, int $id): void
    {
        $this->bot_id = $bot_id;
        $this->id = $id;

        $bot = Bot::select('name')->find($bot_id);
        $this->bot_name = $bot->name;

        if ($id > 0) {
            $data = BotMessage::with('bot_message_type')->with('bot')->find($id)->toArray();
            if ($data) {
                $bot_user = BotUser::select('telegram_chat_id')->where('bot_id', $data['bot_id'])->where('email', auth()->user()->email)->first();
                if ($bot_user) {
                    $this->send_message_self = '<a href="/bot/' . $id . '/send_to_admin" target="_blank">Нажмите <span style="text-decoration: underline">на эту ссылку</a>, чтобы отправить это сообщение себе в боте</a>';
                } else {
                    $this->send_message_self = 'В настоящее время ваш аккаунт администратора не связан с ботом в Telegram, чтобы отправить сообщение самому себе для проверки. Для привязки аккаунта администратора к боту перейдите в бот по ссылке: <a href="https://t.me/' . $data['bot']['alias'] . '">https://t.me/' . $data['bot']['alias'] . '</a> и нажмите Меню - Регистрация';
                }

                $this->bot_message_listener = BotMessageListener::select('listener_id')->where('bot_message_id', $data['id'])->count();

            }


        } else {
            $data = [];
            $data["bot_id"] = $bot_id;
            $data["bot_message_type_id"] = 1;
            $data["pause_after_message"] = 0;
        }

        $this->form->fill($data);

        $this->form_bot_message_link_listener->fill([]);
    }

    public function getHeading(): string
    {
        if ($this->id > 0) {
            return "Редактировать сообщение";
        } else {
            return "Добавить сообщение";
        }
    }

    public function getTitle(): string
    {
        return $this->bot_name;
    }

    protected function getForms(): array
    {
        return ['form', 'form_bot_message_link_listener'];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Ветка')
                    ->description('Если необходимо, укажите ветку, к которой будет прикреплено данное сообщение')
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                        '2xl' => 1,
                    ])
                    ->schema([
                        Select::make('bot_branch_id')
                            ->label('Ветка')
                            ->options(BotBranch::all()->pluck('name', 'id'))
                            ->disabled(auth()->user()->hasPermissionTo('Update:BotMessage')?false:true)
                            ->searchable()
                    ]),
                Section::make('Бот и воронка')
                    ->description('Если необходимо, укажите воронку, к которой будет прикреплено данное сообщение')
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                        '2xl' => 1,
                    ])
                    ->schema([
                        Hidden::make('bot_id'),
                        Select::make('funnel_id')
                            ->label('Воронка')
                            ->options(Funnel::all()->pluck('name', 'id'))
                            ->searchable()
                            ->disabled(auth()->user()->hasPermissionTo('Update:BotMessage')?false:true)

                    ->live(),
                        Select::make('funnel_condition_id')
                            ->label('Условие')
                            ->options(FunnelCondition::all()->pluck('name', 'id'))
                            ->searchable()
                            ->disabled(auth()->user()->hasPermissionTo('Update:BotMessage')?false:true)
                            ->live(),
                        Select::make('funnel_condition_trigger_id')
                            ->label('Условие')
                            ->options(FunnelConditionTrigger::all()->pluck('name', 'id'))
                            ->searchable()
                            ->disabled(auth()->user()->hasPermissionTo('Update:BotMessage')?false:true)
                            ->live(),
                        TextInput::make('funnel_days')
                            ->required()
                            ->label('Дней')
                            ->disabled(auth()->user()->hasPermissionTo('Update:BotMessage')?false:true)
                            ->maxLength(255),
                        TextInput::make('funnel_hours')
                            ->required()
                            ->label('Часов')
                            ->disabled(auth()->user()->hasPermissionTo('Update:BotMessage')?false:true)
                            ->maxLength(255),
                        TextInput::make('funnel_minutes')
                            ->required()
                            ->label('Минут')
                            ->disabled(auth()->user()->hasPermissionTo('Update:BotMessage')?false:true)
                            ->maxLength(255)
                    ]),
                Section::make('Бот и назначение')
                    ->description('Если необходимо, укажите функцию, которую будет выполнять данное сообщение')
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                        '2xl' => 1,
                    ])
                    ->schema([
                        Hidden::make('bot_id'),
                        Select::make('bot_message_appointment_id')
                            ->label('Назначение')
                            ->options(BotMessageAppointment::all()->pluck('name', 'id'))
                            ->disabled(auth()->user()->hasPermissionTo('Update:BotMessage')?false:true)
                            ->searchable()
                            ->live(),
                    ]),
                Section::make('Тип и название сообщения')
                    ->description('Укажите базовые настройки, чтобы продолжить работу')
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ])
                    ->schema([
                        Select::make('bot_message_type_id')
                            ->label('Тип сообщения')
                            ->required()
                            ->options(BotMessageType::all()->pluck('name', 'id'))
                            ->disabled(auth()->user()->hasPermissionTo('Update:BotMessage')?false:true)
                            ->searchable()
                            ->live(),
                        TextInput::make('name')
                            ->required()
                            ->label('Название сообщения (только в панели администратора)')
                            ->maxLength(255)
                            ->disabled(auth()->user()->hasPermissionTo('Update:BotMessage')?false:true)
                    ]),
                Section::make('Текст сообщения')
                    ->description('Сообщение, которое будет отправляться пользователю')
                    ->schema([
                        Textarea::make('text')
                            ->label('Текст сообщения')
                            ->extraInputAttributes(['style' => 'height: 500px'])
                            ->disabled(auth()->user()->hasPermissionTo('Update:BotMessage')?false:true)
                    ]),
                Section::make('Изображение')
                    ->schema([
                        FileUpload::make('image')
                            ->label('Изображение, которое будет отправляться пользователю')
                            ->acceptedFileTypes(['image/*'])
                            ->disk('local')
                            ->directory('bot_message_images')
                            ->visibility('public')
                    ])
                    ->visible(function (Get $get) {
                        if (is_callable($get)) {
                            return $get('bot_message_type_id') == 2;
                        }
                    }),
                Section::make('Видео')
                    ->schema([
                        FileUpload::make('video')
                            ->label('Видео, которое будет отправляться пользователю')
                            ->acceptedFileTypes(['video/mp4'])
                            ->disk('local')
                            ->directory('bot_message_videos')
                            ->visibility('public')
                    ])
                    ->visible(function (Get $get) {
                        if (is_callable($get)) {
                            return $get('bot_message_type_id') == 3;
                        }
                    }),
                Section::make('Аудио')
                    ->schema([
                        FileUpload::make('audio')
                            ->label('Аудио, которое будет отправляться пользователю')
                            ->acceptedFileTypes(['audio/mp3'])
                            ->disk('local')
                            ->directory('bot_message_audios')
                            ->visibility('public')
                    ])
                    ->visible(function (Get $get) {
                        if (is_callable($get)) {
                            return $get('bot_message_type_id') == 4;
                        }
                    }),
                Section::make('Файл')
                    ->schema([
                        FileUpload::make('custom_file')
                            ->label('Файл, который будет отправляться пользователю')
                            ->disk('local')
                            ->directory('bot_message_custom_files')
                            ->visibility('public'),
                        TextInput::make('custom_file_name')
                            ->label('Имя файла, которое увидит пользователь в Telegram')
                            ->maxLength(255)
                    ])
                    ->visible(function (Get $get) {
                        if (is_callable($get)) {
                            return $get('bot_message_type_id') == 5;
                        }
                    }),
                Section::make('Удалить сообщение (не более, чем 47 часов с момента отправки)')
                    ->description('При включении данного параметра сообщение будет удалено у получивших его пользователей. Telegram позволяет полностью удалять сообщения не старше 47 часов с момента отправки.')
                    ->columns([
                        'sm' => 3,
                        'md' => 3,
                        'lg' => 3,
                        'xl' => 3,
                        '2xl' => 3,
                    ])
                    ->schema([
                        Toggle::make('delete_through')
                            ->label('Включить')
                            ->disabled(auth()->user()->hasPermissionTo('Update:BotMessage')?false:true)
                            ->live(),
                        Select::make('delete_through_hours')
                            ->label('Часы')
                            ->disabled(auth()->user()->hasPermissionTo('Update:BotMessage')?false:true)
                            ->options($this->hours)
                            ->searchable()
                            ->visible(function (Get $get) {
                                if (is_callable($get)) {
                                    return $get('delete_through') == 1;
                                }
                            }),
                        Select::make('delete_through_minutes')
                            ->label('Минуты')
                            ->options($this->minutes)
                            ->searchable()
                            ->disabled(auth()->user()->hasPermissionTo('Update:BotMessage')?false:true)
                            ->visible(function (Get $get) {
                                if (is_callable($get)) {
                                    return $get('delete_through') == 1;
                                }
                            }),
                    ])->visible(function (Get $get) {
                        if (is_callable($get)) {
                            return $get('delete_keyboard_through') == 0;
                        }
                    }),
                Section::make('Удалить клавиатуру сообщения (более 48 часов с момента отправки)')
                    ->description('При включении данного параметра клавиатура сообщения будет удалена у получивших его пользователей. Telegram позволяет удалять клавиатуру в течение любого времени с момента отправки сообщения.')
                    ->columns([
                        'sm' => 4,
                        'md' => 4,
                        'lg' => 4,
                        'xl' => 4,
                        '2xl' => 4,
                    ])
                    ->schema([
                        Toggle::make('delete_keyboard_through')
                            ->label('Включить')
                            ->disabled(auth()->user()->hasPermissionTo('Update:BotMessage')?false:true)
                            ->live(),
                        Textarea::make('delete_keyboard_through_days')
                            ->label('Дни')
                            ->disabled(auth()->user()->hasPermissionTo('Update:BotMessage')?false:true)
                            ->visible(function (Get $get) {
                                if (is_callable($get)) {
                                    return $get('delete_keyboard_through') == 1;
                                }
                            }),
                        Select::make('delete_keyboard_through_hours')
                            ->label('Часы')
                            ->disabled(auth()->user()->hasPermissionTo('Update:BotMessage')?false:true)
                            ->options($this->hours)
                            ->searchable()
                            ->visible(function (Get $get) {
                                if (is_callable($get)) {
                                    return $get('delete_keyboard_through') == 1;
                                }
                            }),
                        Select::make('delete_keyboard_through_minutes')
                            ->label('Минуты')
                            ->disabled(auth()->user()->hasPermissionTo('Update:BotMessage')?false:true)
                            ->options($this->minutes)
                            ->searchable()
                            ->visible(function (Get $get) {
                                if (is_callable($get)) {
                                    return $get('delete_keyboard_through') == 1;
                                }
                            }),
                    ])
                    ->visible(function (Get $get) {
                        if (is_callable($get)) {
                            return $get('delete_through') == 0;
                        }
                    }),
                Section::make('Пауза после отправки сообщения')
                    ->description('При установке значения больше нуля бот создаст задержку после отправки данного сообщения, чтобы сделать паузу перед доставкой следующего сообщения.')
                    ->schema([
                        TextInput::make('pause_after_message')
                            ->label('Введите значение в секундах')
                            ->disabled(auth()->user()->hasPermissionTo('Update:BotMessage')?false:true)
                            ->maxLength(255)
                    ]),
                Section::make('Отправить сообщение себе')
                    ->description(new HtmlString($this->send_message_self))
                    ->columns([
                        'sm' => 4,
                        'md' => 4,
                        'lg' => 4,
                        'xl' => 4,
                        '2xl' => 4,
                    ])
                    ->schema([]),
                Section::make('Дополнительные настройки')
                    ->description(new HtmlString("<a style='font-weight: bold' href='/admin/bots/".$this->bot_id."/".$this->id."/message-listeners'>Ожидания: ".$this->bot_message_listener." ▶️</a>"))
                    ->columns([
                        'sm' => 4,
                        'md' => 4,
                        'lg' => 4,
                        'xl' => 4,
                        '2xl' => 4,
                    ])
                    ->visible(fn() => auth()->user()->can('Create:BotMessage'))
                    ->schema([]),

                Actions::make([
                    Action::make('Сохранить')
                        ->action(function () {
                            $botMessageSave = new BotMessageSave();

                            $data = $this->form->getState();

                            if (!isset($data['delete_through'])) $data['delete_through'] = 0;
                            if (!isset($data['delete_keyboard_through'])) $data['delete_keyboard_through'] = 0;

                            if ($this->id>0) {
                                BotMessage::where('id', $this->id)->update($data);
                                $botMessageSave->handle($data, $this->id);
                            } else {
                                $new_message = BotMessage::create($data);
                            }

                            Notification::make()
                                ->title('Данные успешно сохранены!')
                                ->success()
                                ->send();

                            if ($this->id>0) {
                                return redirect('/admin/bots/'.$this->bot_id.'/messages');
                            } else {
                                return redirect('/admin/bots/'.$this->bot_id.'/'.$new_message->id.'/message-admin');
                            }

                        })
                        ->visible(fn() => auth()->user()->can('Create:BotMessage')),

                    Action::make('Cancel')
                        ->action(function () {
                            return redirect('/admin/bots/'.$this->bot_id.'/messages');
                        })
                        ->label('Вернуться назад')
                ])
            ])->statePath('data');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(BotMessageButton::query()->where('bot_message_id', $this->id))
            ->persistSearchInSession()
            ->columns([
                TextColumn::make('pos')
                    ->label('№')
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Название')
                    ->searchable()
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()->url(fn($record) => "/admin/bots/".$this->id."/".$record->id."/button-admin")
                    ->visible(auth()->user()->can('Update:BotMessageButton')),
                DeleteAction::make()
                    ->visible(auth()->user()->can('Delete:BotMessageButton')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                ])
            ])
            ->recordUrl(fn($record) => "/admin/bots/".$this->id."/".$record->id."/button-admin")
            ->defaultSort('pos')
            ->reorderable('pos');
    }

    public function table_listener(Table $table): Table
    {
        return $table
            ->query(BotMessageButton::query()->where('bot_message_id', $this->id))
            ->persistSearchInSession()
            ->columns([
                TextColumn::make('pos')
                    ->label('№')
                    ->searchable()
            ])
            ->filters([
                //
            ])
            ->recordActions([
            ])
            ->toolbarActions([
            ])
            ->reorderable('pos');
    }

    public function form_bot_message_link_listener(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Выберите параметр')
                    ->description('')
                    ->schema([
                        Select::make('listener_id')
                            ->label('Параметр')
                            ->required()
                            ->validationMessages([
                                'required' => 'Обязательно выберите значение из списка',
                            ])
                            ->options(
                                Listener::query()->pluck('name', 'id')
                            )
                            ->searchable()
                    ]),
                Actions::make([
                    Action::make('Сохранить')
                        ->action(function () {
                            $formdata = $this->form_bot_message_link_listener->getState();

                            BotMessageListener::upsert(
                                ['bot_message_id' => $this->id, 'listener_id' => $formdata['listener_id']],
                                ['listener_id', 'bot_message_id'],
                                ['updated_at' => now()]
                            );

                            $this->dispatch('close-modal', id: 'add-page-modal');
                        })
                        ->disabled(fn() => auth()->user()->can('Create:BotMessageListener')),
                    Action::make('Отмена')
                        ->action(function () {
                            $this->dispatch('close-modal', id: 'add-page-modal');
                        })
                ])
            ])->statePath('data_bot_message_link_listener');
    }

}
