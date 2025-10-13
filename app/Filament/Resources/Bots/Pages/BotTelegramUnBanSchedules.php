<?php

namespace App\Filament\Resources\Bots\Pages;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use DB;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use Filament\Schemas\Components\Text;

use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Bots\BotResource;
use App\Models\Core\Bot;
use App\Models\Core\BotUser;
use App\Models\Core\BotUserUnbanSchedule;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;



class BotTelegramUnBanSchedules extends Page implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;


    protected static string $resource = BotResource::class;

    protected string $view = 'filament.resources.bot-resource.pages.bot-telegram-unban-schedules';


    public static ?string $label = "Разбаны";
    public static ?string $navigationLabel = "Разбаны";
    public static ?string $title = "";

    public int $bot_id;
    public string $bot_name;

    public ?array $data_unban_user = [];

    public function mount(int $bot_id): void
    {
        $this->bot_id = $bot_id;
        $bot = Bot::select('name')->find($bot_id);

        $this->bot_name = $bot->name;

        $this->form_unban_user->fill([]);

    }

    protected function getForms(): array
    {
        return ['form_unban_user'];
    }

    public function getHeading(): string
    {
        return $this->bot_name;
    }

    public function getTitle(): string
    {
        return $this->bot_name;
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('updated_at', 'desc')
            ->query(
                BotUserUnbanSchedule::with('run_status_name')
                ->whereHas('bot_user', function ($query) {
                    $query->where('bot_id', $this->bot_id);
                })
            )
            ->columns([
                TextColumn::make('bot_user.first_name')
                    ->label('Имя')
                    ->searchable(),
                TextColumn::make('bot_user.last_name')
                    ->label('Фамилия')
                    ->searchable(),
                TextColumn::make('bot_user.username')
                    ->label('Ник')
                    ->searchable(),
                TextColumn::make('bot_user.email')
                    ->label('Email'),
                TextColumn::make('unban_datetime')
                    ->label('Дата и время разбана')
                    ->dateTime('d.m.Y H:i:s'),
                 TextColumn::make('run_status_name.name')
                     ->label('Статус')
                     ->color(fn (string $state): string => match ($state) {
                         'Да' => 'danger',
                         'Нет' => 'success',
                     })
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()->url(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/telegram-ban-schedule-admin"),
                DeleteAction::make(),

            ])
           // ->recordUrl(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/telegram-ban-schedule-admin")
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public function form_unban_user(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Пользователи')
                    ->description('')
                    ->schema([
                        Hidden::make('ban_datetime')
                            ->format('Y-m-d H:i:s'),
                        Select::make('bot_user_id')
                            ->label('Пользователь')
                            ->required()
                            ->validationMessages([
                                'required' => 'Обязательно выберите пользователя',
                            ])
                            ->searchable()
                            ->options(BotUser::where('bot_id', $this->bot_id)->get()->map(function ($bot_user) {
                                return ['key' => $bot_user->id, 'value' => (isset($bot_user->first_name) && $bot_user->first_name!='none'?$bot_user->first_name:'')." ".(isset($bot_user->last_name) && $bot_user->last_name!='none'?$bot_user->last_name:'')." ".(isset($bot_user->username) && $bot_user->username!='none'?"(".$bot_user->username.")":'')];
                            })->pluck('value', 'key')->toArray())
                    ]),
                Actions::make([
                    Action::make('Сохранить')
                        ->action(function () {
                            $formdata = $this->form_unban_user->getState();

                            $count_unban = BotUserUnbanSchedule::where('bot_user_id',$formdata['bot_user_id'])->count();

                            $count_bot_user = BotUser::where('id',$formdata['bot_user_id'])->count();

                            if($count_unban == 0 && $count_bot_user >= 1) {
                                BotUserUnbanSchedule::upsert(
                                    ['unban_datetime' => now(), 'run_status' => 0, 'bot_user_id' => $formdata['bot_user_id']],
                                    ['unban_datetime', 'bot_user_id'],
                                    ['updated_at' => now()]
                                );

                                Notification::make()
                                    ->title('Данные успешно сохранены!')
                                    ->success()
                                    ->send();

                            } else {

                                Notification::make()
                                    ->title('Разбанить пользователя можно только один раз!')
                                    ->success()
                                    ->send();
                            }

                            $this->dispatch('close-modal', id: 'add-page-modal');
                        }),
                    Action::make('Отмена')
                        ->action(function () {
                            $this->dispatch('close-modal', id: 'add-page-modal');
                        })
                ])
            ])->statePath('data_unban_user');
    }
}
