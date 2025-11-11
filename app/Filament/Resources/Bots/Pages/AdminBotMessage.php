<?php
namespace App\Filament\Resources\Bots\Pages;

use App\Models\Core\BotBranch;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use App\Actions\Core\BotMessage\BotMessageSave;
use App\Filament\Resources\Bots\BotResource;
use App\Filament\Resources\BotResource\RelationManagers;
use App\Livewire\Agency\Inbox\ActiveTasksTable;
use App\Models\Core\Bot;
use App\Models\Core\BotMessage;
use App\Models\Core\BotMessageAppointment;
use App\Models\Core\BotMessageType;
use App\Models\Core\TelegramSupergroup;
use App\Models\Core\TelegramSupergroupLinkBot\TelegramSupergroupLinkBot;
use App\Models\Core\User;

use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;


class AdminBotMessage extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;


    protected static string $resource = BotResource::class;

    protected string $view = 'filament.resources.bot-resource.pages.admin-bot';

    protected static ?string $model = Bot::class;

    public static ?string $navigationLabel = "";
    public static ?string $title = "Настройки бота";
    public ?array $data = [];

    public ?array $data_bot_link_supergroup = [];
    public ?array $data_telegram_chats = [];


    public $record;

    public int $id;

    public int $category;

    public array $categories = [1 => 'Основные', 2 => 'Сообщения', 3 => 'Подписчики', 4 => 'Супергруппы', 5 => 'Платежи', 6 => 'Платежи в ожидании'];

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
        return Bot::class;
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getForms(): array
    {
        return ['form','form_bot_link_supergroup','form_telegram_chats'];
    }

    protected function getTables(): array
    {
        return ['table','table_telegram_chats'];
    }

    public function mount(int $record, int $category): void
    {
        $this->id = $record;
        $this->category = $category;

        $data = ($record>0?Bot::find($record)->toArray():[]);
        $this->form->fill($data);
        $this->form_bot_link_supergroup->fill([]);
        $this->form_telegram_chats->fill([]);
    }

    public function form(Schema $schema): Schema
    {

        return $schema
            ->components([
                Section::make('Основные параметры')
                    ->description('Укажите основные настройки и заполните все поля')
                    ->columns([
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ])
                    ->schema([
                        TextInput::make('name')
                            ->label('Название (Только в панели администратора)')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('alias')
                            ->label('Username в Telegram')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('telegram_token')
                            ->label('Telegram-токен (из BotFather)')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('telegram_webhook')
                            ->label('Адрес вебхука Telegram')
                            ->required()
                            ->maxLength(255),
                    ]),
                Actions::make([
                    Action::make('Сохранить')
                        ->action(function () {
                            $data = $this->form->getState();

                            if ($this->id > 0) {
                                Bot::where('id', $this->id)->update($data);
                                return redirect('/admin/bots');
                            } else {
                                $new = Bot::create($data);

                                return redirect('/admin/bots/'.$new->id.'/edit');
                            }

                            Notification::make()
                                ->title('Данные успешно сохранены!')
                                ->success()
                                ->send();
                        })
                        ->visible(fn() => auth()->user()->can('Create:Bot')),

                ])
            ])->statePath('data');

    }
    public function form_bot_link_supergroup(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Выберите супергруппу')
                    ->description('')
                    ->schema([
                        Select::make('telegram_supergroup_id')
                            ->label('Супергруппа')
                            ->required()
                            ->options(
                                TelegramSupergroup::query()->pluck('name', 'id')
                            )
                            ->searchable()
                    ]),
                Actions::make([
                    Action::make('Сохранить')
                        ->action(function () {
                            $formdata = $this->form_bot_link_supergroup->getState();

                            TelegramSupergroupLinkBot::upsert(
                                ['telegram_supergroup_id' => $formdata['telegram_supergroup_id'], 'bot_id' => $this->record],
                                ['telegram_supergroup_id', 'bot_id'],
                                ['updated_at' => now()]
                            );

                            $this->dispatch('close-modal', id: 'add-page-modal');
                        }),
                    Action::make('Отмена')
                        ->action(function () {
                            $this->dispatch('close-modal', id: 'add-page-modal');
                        })
                ])
            ])->statePath('data_bot_link_supergroup');
    }

    public function form_telegram_chats(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Бот и назначение')
                    ->description('Выберите бот, через который будет отправляться сообщение. Если необходимо, укажите функцию, которую будет выполнять данное сообщение')
                    ->columns([
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ])
                    ->schema([
                        Select::make('bot_id')
                            ->label('Название бота')
                            ->required()
                            ->options(Bot::all()->pluck('name', 'id'))
                            ->searchable(),
                        Select::make('bot_message_appointment_id')
                            ->label('Назначение')
                            ->options(BotMessageAppointment::all()->pluck('name', 'id'))
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
                            ->searchable()
                            ->live(),
                        TextInput::make('name')
                            ->required()
                            ->label('Название сообщения (только в панели администратора)')
                            ->maxLength(255)
                    ]),
                Section::make('Текст сообщения')
                    ->description('Сообщение, которое будет отправляться пользователю')
                    ->schema([
                        Textarea::make('text')
                            ->label('Текст сообщения')
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
                            ->directory('bot_message_videos')
                            ->visibility('public')
                    ])
                    ->visible(function (Get $get) {
                        if (is_callable($get)) {
                            return $get('bot_message_type_id') == 4;
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
                            ->live(),
                        Select::make('delete_through_hours')
                            ->label('Часы')
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
                            ->visible(function (Get $get) {
                                if (is_callable($get)) {
                                    return $get('delete_through') == 1;
                                }
                            }),
                    ])
                    ->visible(function (Get $get) {
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
                            ->live(),
                        Textarea::make('delete_keyboard_through_days')
                            ->label('Дни')
                            ->visible(function (Get $get) {
                                if (is_callable($get)) {
                                    return $get('delete_keyboard_through') == 1;
                                }
                            }),
                        Select::make('delete_keyboard_through_hours')
                            ->label('Часы')
                            ->options($this->hours)
                            ->searchable()
                            ->visible(function (Get $get) {
                                if (is_callable($get)) {
                                    return $get('delete_keyboard_through') == 1;
                                }
                            }),
                        Select::make('delete_keyboard_through_minutes')
                            ->label('Минуты')
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
                Actions::make([
                    Action::make('Сохранить')
                        ->action(function () {
                            $botMessageSave = new BotMessageSave();

                            $data = $this->form_telegram_chats->getState();

                            if (!isset($data['delete_through'])) $data['delete_through'] = 0;
                            if (!isset($data['delete_keyboard_through'])) $data['delete_keyboard_through'] = 0;

                            $new_message = BotMessage::create($data);

                            Notification::make()
                                ->title('Данные успешно сохранены!')
                                ->success()
                                ->send();

                            return redirect('/admin/bot-messages/'.$new_message->id.'/admin');


                        }),
                ])
            ])->statePath('data_telegram_chats');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(BotMessage::query())
            ->persistSearchInSession()
            ->columns([
                TextColumn::make('bot.name')
                    ->label('Бот')
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Название (Только в панели администратора)')
                    ->searchable(),
                TextColumn::make('bot_message_type.name')
                    ->label('Тип сообщения'),
                TextColumn::make('bot_message_appointment.name')
                    ->label('Назначение'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()->url(fn($record) => "/admin/bot-messages/".$record->id."/admin")
                    ->disabled(fn() => auth()->user()->can('Update:Bot')),

            ])
            ->recordUrl(fn($record) => "/admin/bot-messages/".$record->id."/admin")
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
        }

    public function table_telegram_chats(Table $table): Table
    {
        return $table
            ->query(TelegramSupergroupLinkBot::query()->with('telegramsupergroups')->where('bot_id', $this->record))
            ->persistSearchInSession()
            ->columns([
                TextColumn::make('telegramsupergroups.name')
                    ->label('Супергруппа')
                    ->searchable()
            ])
            ->filters([
                //
            ])
            ->recordActions([
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }



}
