<?php
namespace App\Filament\Resources\BotResource\Pages;

use App\Models\Core\BotMessage;
use App\Models\Core\TelegramSendMessageSchedule;
use Illuminate\Support\HtmlString;

use App\Filament\Resources\BotResource;

use Filament\Forms;
use Filament\Forms\Components;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;

use Filament\Forms\Form;
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


class BotSendingAdmin extends Page implements HasForms, HasTable, HasInfolists
{
    use InteractsWithForms;
    use InteractsWithInfolists;
    use InteractsWithTable;

    protected static string $resource = BotResource::class;

    protected static string $view = 'filament.resources.bot-resource.pages.sending-admin';

    public int $id;

    public int $bot_id;
    public int $bot_message_id;
    public string $bot_name;
    public int $sent_users;
    public int $new_bot_id;

    protected static ?string $model = Sending::class;

    public static ?string $label = "Рассылка";
    public static ?string $navigationLabel = "Рассылка";
    public static ?string $title = "Рассылка";
    public ?array $data = [];
    public ?array $data_bot_message_link_listener = [];
    public ?array $data_bot_user = [];



    public function getRecord(): ?Model
    {
        return Sending::class;
    }

    public function getHeading(): string
    {
        if ($this->id > 0) {
            return "Редактировать рассылку";
        } else {
            return "Добавить рассылку";
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
            $data = ($id>0?Sending::with('bot_message')->find($id)->toArray():["bot_id" => $bot_id]);
            $this->sent_users = TelegramSendMessageSchedule::where('run_status', '>', 0)->where('sending_id', $id)->count();
        } else {
            $data = [];
            $data['bot_id'] = $bot_id;
            $this->sent_users = 0;
        }

        $bot = Bot::select('name')->find($bot_id);
        $this->bot_name = $bot->name;

        $this->form_bot_user->fill([]);
        $this->form->fill($data);
    }

    public function getTitle(): string
    {
        return $this->bot_name;
    }

    protected function getForms(): array
    {
        return ['form', 'form_bot_user'];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Рассылка')
                    ->description('')
                    ->columns([
                        'sm' => 3,
                        'md' => 3,
                        'lg' => 3,
                        'xl' => 3,
                        '2xl' => 3,
                    ])
                    ->schema([
                        Forms\Components\Hidden::make('id'),
                       // Forms\Components\Hidden::make('bot_id'),
                        Forms\Components\TextInput::make('name')
                            ->label('Название рассылки')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('bot_message_id')
                            ->label('Сообщение')
                            ->options(BotMessage::where('bot_id', $this->bot_id)->pluck('name', 'id'))
                            ->required()
                            ->validationMessages([
                                'required' => 'Обязательно выберите сообщение',
                            ])
                            ->searchable(),
                        Forms\Components\DateTimePicker::make('send_datetime')
                            ->label('Дата и время отправки')
                            ->format('Y-m-d H:i:s')

                            ->required()
                            ->validationMessages([
                                'required' => 'Обязательно укажите дату',
                            ])
                    ]),
                Section::make('Вы не можете изменить эту рассылку')
                    ->description('В рассылке уже есть пользователи, получившие сообщение. Если нужно отменить отправку тем, кто еще не получил письмо, нажмите кнопку Удалить рассылку')
                    ->columns([
                        'sm' => 3,
                        'md' => 3,
                        'lg' => 3,
                        'xl' => 3,
                        '2xl' => 3,
                    ])
                    ->schema([
                    ])
                ->visible($this->id > 0 && $this->sent_users > 0),

                Actions::make([
                    Action::make('Сохранить')
                        ->action(function () {
                            $data = $this->form->getState();
                           // $this->bot_id = $data['bot_id'];
                            $this->bot_message_id = $data['bot_message_id'];
                            $datetime = date('Y-m-d H:i:s', time());


                            if ($data['send_datetime'] >= $datetime) {
                                if ($this->id>0) {

                                    if ($this->sent_users > 0) {
                                        Notification::make()
                                            ->title('Рассылка уже доставлена пользователям, удаление невозможно.')
                                            ->danger()
                                            ->send();
                                    } else {
                                        Sending::where('id', $this->id)->update($data);

                                        Notification::make()
                                            ->title('Данные успешно сохранены!')
                                            ->success()
                                            ->send();

                                        return redirect('/admin/bots/' . $this->bot_id . '/sendings');
                                    }
                                } else {
                                    $new_sending = Sending::create($data);

                                    Notification::make()
                                        ->title('Данные успешно сохранены!')
                                        ->success()
                                        ->send();

                                    return redirect('/admin/bots/' . $this->bot_id . '/' . $new_sending->id . '/sending-admin');
                                }
                            } else {
                                Notification::make()
                                    ->title('Дата и время отправки меньше текущей!')
                                    ->danger()
                                    ->send();
                            }

                        }),
                    Action::make('Cancel')
                        ->action(function () {
                            return redirect('/admin/bots/' . $this->bot_id . '/sendings');
                        })
                        ->label('Вернуться назад'),
                    Action::make('Delete')
                        ->action(function () {

                            if ($this->sent_users > 0) {
                                TelegramSendMessageSchedule::where('sending_id', $this->id)->where('run_status', 0)->update(['run_status' => 3]);
                            } else {
                                TelegramSendMessageSchedule::where('sending_id', $this->id)->delete();
                                Sending::destroy($this->id);
                            }

                            return redirect('/admin/bots/' . $this->bot_id . '/sendings');
                        })
                        ->label('Удалить')
                ])
            ])->statePath('data');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                TelegramSendMessageSchedule::query()->with('sending')->with('bot_user')
                    ->whereHas('bot_message', function ($query) {
                    $query->where('bot_id', $this->bot_id);
                })->where('sending_id', $this->id)
            )
            ->columns([
                Tables\Columns\TextColumn::make('bot_user.username')
                    ->label('Пользователь')
                    ->searchable(),
                Tables\Columns\TextColumn::make('run_status')
                    ->label('Статус'),
                Tables\Columns\TextColumn::make('bot_message.name')
                    ->label('Сообщение')
                    ->searchable(),
            ])

            ->filters([
                //
            ])
            ->actions([

            ])->recordUrl(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/sending-admin");
    }

    public function form_bot_user(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Получатели рассылки')
                    ->description('')
                    ->schema([
                        Forms\Components\Hidden::make('sending_id'),
                        Select::make('bot_user_id')
                            ->label('Пользователь')
                            ->required()
                            ->validationMessages([
                                'required' => 'Обязательно выберите пользователя',
                            ])
                            ->options(
                                BotUser::query()->pluck('username', 'id')
                            )
                            ->searchable(),
                        Checkbox::make('run_status')
                            ->label('Статус'),
                        Select::make('bot_message_id')
                            ->label('Сообщение')
                            ->required()
                            ->validationMessages([
                                'required' => 'Обязательно выберите сообщение',
                            ])
                            ->options(
                                BotMessage::query()->pluck('name', 'id')
                            )
                            ->searchable(),
                    ]),
                Actions::make([
                    Action::make('Сохранить')
                        ->action(function () {
                            $formdata = $this->form_bot_user->getState();

                            TelegramSendMessageSchedule::upsert(
                                ['sending_id' => $this->id, 'bot_user_id' => $formdata['bot_user_id'], 'bot_message_id' => $formdata['bot_message_id'], 'run_status' => $formdata['run_status']],
                                ['sending_id', 'bot_user_id', 'bot_message_id', 'run_status'],
                                ['updated_at' => now()]
                            );

                            $this->dispatch('close-modal', id: 'add-page-modal');
                        }),
                    Action::make('Отмена')
                        ->action(function () {
                            $this->dispatch('close-modal', id: 'add-page-modal');
                        })
                ])
            ])->statePath('data_bot_user');
    }

}
