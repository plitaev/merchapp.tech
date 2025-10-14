<?php
namespace App\Filament\Resources\Bots\Pages;

use App\Models\Core\BotBranch;
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

use App\Actions\Core\Sending\SendingSave;

use App\Models\Core\Bot;
use App\Models\Core\Sending;
use App\Models\Core\SendingAppointment;
use App\Models\Core\SendingButton;
use App\Models\Core\SendingListener;
use App\Models\Core\SendingType;
use App\Models\Core\BotUser;
use App\Models\Core\Funnel;
use App\Models\Core\FunnelCondition;
use App\Models\Core\FunnelConditionTrigger;
use App\Models\Core\Listener;


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
    public int $sent_users;
    public int $new_bot_id;

    protected static ?string $model = BotBranch::class;

    public static ?string $label = "Акция";
    public static ?string $navigationLabel = "Акция";
    public static ?string $title = "Акция";
    public ?array $data = [];
    public ?array $data_bot_message_link_listener = [];
    public ?array $data_bot_user = [];

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

        if ($id > 0) {
            $data = ($id>0?BotBranch::find($id)->toArray():["bot_id" => $bot_id]);
        } else {
            $data = [];
            $data['bot_id'] = $bot_id;
        }

        $bot = Bot::select('name')->find($bot_id);
        $this->bot_name = $bot->name;
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
                        'sm' => 3,
                        'md' => 3,
                        'lg' => 3,
                        'xl' => 3,
                        '2xl' => 3,
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
                                    BotBranch::where('id', $this->id)->update($data);
                                }
                            } else {
                                $data['hash'] = $hash;
                                $new = BotBranch::create($data);
                            }

                        }),
                    Action::make('Cancel')
                        ->action(function () {
                            return redirect('/admin/bots/'.$this->bot_id.'/branches');
                        })
                        ->label('Вернуться назад')
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
