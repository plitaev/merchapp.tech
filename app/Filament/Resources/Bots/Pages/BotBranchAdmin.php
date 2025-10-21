<?php
namespace App\Filament\Resources\Bots\Pages;

use App\Models\Core\BotBranch;
use App\Models\Core\BotBranchAccess;
use App\Models\Core\BotBranchLinkProduct;
use App\Models\Core\BotMessageAppointment;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\DeleteAction;
use App\Models\Core\BotMessage;
use App\Models\Core\TelegramSendMessageSchedule;
use Illuminate\Support\HtmlString;

use App\Filament\Resources\Bots\BotResource;

use Filament\Forms;
use Filament\Forms\Components;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Checkbox;
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
use Filament\Schemas\Components\Utilities\Get;

use App\Actions\Core\Sending\SendingSave;

use App\Models\Core\Bot;
use App\Models\Core\SendingAppointment;
use App\Models\Core\SendingButton;
use App\Models\Core\SendingListener;
use App\Models\Core\SendingType;


class BotBranchAdmin extends Page implements HasForms, HasTable, HasInfolists
{
    use InteractsWithForms;
    use InteractsWithInfolists;
    use InteractsWithTable;

    protected static string $resource = BotResource::class;

    protected string $view = 'filament.resources.bot-resource.pages.bot-branch-admin';

    public int $id;

    public int $bot_id;
    public int $bot_message_id;
    public string $bot_name;
    public string $bot_alias;
    public string $bot_branch_hash;
    public int $sent_users;
    public int $new_bot_id;

    protected static ?string $model = BotBranch::class;

    public static ?string $label = "Акция";
    public static ?string $navigationLabel = "Акция";
    public static ?string $title = "Акция";
    public ?array $data = [];
    public ?array $data_bot_message_link_listener = [];
    public ?array $data_bot_user = [];

    public ?array $end_by_products = [];
    public ?array $end_by_products_in_branch = [];

    public function getRecord(): ?Model
    {
        return BotBranch::class;
    }

    public function getHeading(): string
    {
        if ($this->id > 0) {
            return "Редактировать акцию";
        } else {
            return "Добавить акцию";
        }
    }

    protected function getHeaderActions(): array
    {
        return [];
    }


    public function mount(int $bot_id, int $id): void
    {
        $this->bot_id = $bot_id;
        $this->id = $id;

        $this->end_by_products = Products::all()->pluck('name', 'id')->toArray();
        $this->end_by_products_in_branch = BotBranchLinkProduct::select('product_id')->where('bot_branch_id', $id)->pluck('product_id')->toArray();

        if ($id > 0) {
            $data = BotBranch::find($id)->toArray();
            $this->bot_branch_hash = $data["hash"];
        } else {
            $data = [];
            $data['bot_id'] = $bot_id;
            $this->bot_branch_hash = '';
        }

        $bot = Bot::select('name', 'alias')->find($bot_id);
        $this->bot_name = $bot->name;
        $this->bot_alias = $bot->alias;

        $this->form->fill($data);
    }

    public function getTitle(): string
    {
        return $this->bot_name;
    }

    protected function getForms(): array
    {
        return ['form'];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Акция')
                    ->description('Укажите название и пседоним (системное имя)')
                    ->columns([
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ])
                    ->schema([
                        Hidden::make('id'),
                        TextInput::make('name')
                            ->label('Название рассылки')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('alias')
                            ->label('Псевдоним')
                            ->required()
                            ->maxLength(255),
                        DateTimePicker::make('datetime_start')
                            ->label('Дата и время начала акции')
                            ->format('Y-m-d H:i:s')
                            ->required()
                            ->validationMessages([
                                'required' => 'Обязательно укажите дату и время начала акции',
                            ]),
                        DateTimePicker::make('datetime_end')
                            ->label('Дата и время окончания акции')
                            ->format('Y-m-d H:i:s')
                            ->required()
                            ->validationMessages([
                                'required' => 'Обязательно укажите дату и время окончания акции',
                            ])
                    ]),
                Section::make('Акция')
                    ->description(new HtmlString('Ссылка на запуск к акции в боте: <a href="https://t.me/'.$this->bot_alias.'?start='.$this->bot_branch_hash.'" style="text-decoration: underline" target="_blank">https://t.me/'.$this->bot_alias.'?start='.$this->bot_branch_hash.'</a>'))
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                        '2xl' => 1
                    ])
                    ->schema([]),
                Section::make('Доступ для новых пользователей')
                    ->description('Укажите, должны ли новые пользователи получать доступ к участию в акции')
                    ->columns([
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ])
                    ->schema([
                        Select::make('new_users_bot_branch_access_id')
                            ->label('Доступ для новых пользователей')
                            ->options(BotBranchAccess::all()->pluck('name', 'id'))
                            ->searchable(),
                        Select::make('new_users_bot_message_id')
                            ->label('Отправлять сообщение')
                            ->options(BotMessage::where('bot_id', $this->bot_id)->pluck('name', 'id'))
                            ->searchable(),
                        Select::make('guests_bot_branch_access_id')
                            ->label('Доступ для гостей')
                            ->options(BotBranchAccess::all()->pluck('name', 'id'))
                            ->searchable(),
                        Select::make('guests_bot_message_id')
                            ->label('Отправлять сообщение')
                            ->options(BotMessage::where('bot_id', $this->bot_id)->pluck('name', 'id'))
                            ->searchable(),
                        Select::make('members_bot_branch_access_id')
                            ->label('Доступ для текущих участников')
                            ->options(BotBranchAccess::all()->pluck('name', 'id'))
                            ->searchable(),
                        Select::make('members_bot_message_id')
                            ->label('Отправлять сообщение')
                            ->options(BotMessage::where('bot_id', $this->bot_id)->pluck('name', 'id'))
                            ->searchable(),
                        Select::make('banneds_bot_branch_access_id')
                            ->label('Доступ для выбывших участников')
                            ->options(BotBranchAccess::all()->pluck('name', 'id'))
                            ->searchable(),
                        Select::make('banneds_bot_message_id')
                            ->label('Отправлять сообщение')
                            ->options(BotMessage::where('bot_id', $this->bot_id)->pluck('name', 'id'))
                            ->searchable()
                    ]),
                Section::make('Завершение акции')
                    ->description('Укажите, когда пользователь должен выйти из ветки акции')
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                        '2xl' => 1,
                    ])
                    ->schema([
                        Forms\Components\Checkbox::make('end_by_restart')
                            ->label('По нажатию Меню - Старт'),
                        Forms\Components\CheckboxList::make('end_by_products')
                            ->options($this->end_by_products)
                            ->afterStateHydrated(function ($component, $state) {
                                if (! filled($state)) {
                                    $component->state($this->end_by_products_in_branch);
                                }
                            })
                    ]),

                Actions::make([
                    Action::make('Сохранить')
                        ->action(function () {
                            $data = $this->form->getState();
                            $hash=hash('sha256', $data['alias']);

                            if ($this->id > 0) {
                                $branch = BotBranch::find($this->id);
                                if (!$branch->hash) {
                                    $data['hash'] = $hash;
                                }

                                BotBranch::where('id', $this->id)->update($data);
                                return redirect('/admin/bots/'.$this->bot_id.'/branches');

                            } else {
                                $data['hash'] = $hash;
                                $new = BotBranch::create($data);

                                return redirect('/admin/bots/'.$this->bot_id.'/'.$new->id.'/branch-admin');
                            }

                        }),
                    Action::make('Cancel')
                        ->action(function () {
                            return redirect('/admin/bots/'.$this->bot_id.'/branches');
                        })
                        ->label('Вернуться назад'),
                    Action::make('Stop')
                        ->action(function () {
                            return redirect('/admin/bots/'.$this->bot_id.'/branches');
                        })
                        ->label('Завершить акцию сейчас')
                ])
            ])->statePath('data');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                TelegramSendMessageSchedule::query()->with('sending')->with('bot_user')->with('run_status_name')
                    ->whereHas('bot_message', function ($query) {
                        $query->where('bot_id', $this->bot_id);
                    })->where('sending_id', $this->id)
            )
            ->columns([
                TextColumn::make('concat(bot_user.email, \' -\', bot_user.username) as full_name')
                    ->visible(false)
                    ->searchable(),
                TextColumn::make('bot_user.first_name')
                    ->label('Имя'),
                TextColumn::make('bot_user.last_name')
                    ->label('Фамилия'),
                TextColumn::make('bot_user.username')
                    ->label('Имя пользователя'),
                TextColumn::make('bot_user.email')
                    ->label('Email'),
                TextColumn::make('run_status_name.name')
                    ->label('Статус'),
            ])

            ->filters([
                //
            ])
            ->recordActions([
                DeleteAction::make()
            ]);
    }

}
