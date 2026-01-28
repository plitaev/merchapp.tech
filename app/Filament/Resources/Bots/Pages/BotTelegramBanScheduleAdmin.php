<?php
namespace App\Filament\Resources\Bots\Pages;

use App\Models\Core\TelegramUnbanSchedule;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use DB;
use Filament\Forms\Components\Checkbox;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use App\Actions\Core\TelegramBanSchedule\TelegramBanScheduleCreateByEmail;
use App\Filament\Resources\Bots\BotResource;
use App\Models\Core\Bot;
use App\Models\Core\BotUser;
use App\Models\Core\BotUserBanSchedule;
use App\Models\Core\BotAdminLog;
use App\Models\Core\User;

use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;
class BotTelegramBanScheduleAdmin extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = BotResource::class;

    protected string $view = 'filament.resources.bot-resource.pages.bot-telegram-ban-schedule-admin';

    protected static ?string $model = BotUserBanSchedule::class;

    public static ?string $label = "Бан";
    public static ?string $navigationLabel = "Бан";
    public static ?string $title = "Бан";

    public ?array $data = [];


    public int $bot_id;
    public string $bot_name;

    public int $id;

    public string $name;

    public function getRecord(): ?Model
    {
        return BotUserBanSchedule::class;
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
            $data = BotUserBanSchedule::find($id)->toArray();
        } else {
            $data = [];
            $data['bot_id'] = $bot_id;
        }

        $bot = Bot::select('name')->find($bot_id);
        $this->bot_name = $bot->name;

        $this->form->fill($data);
        $this->form_ban_user->fill([]);

        if (!Auth::user()->hasPermissionTo('View:BotUserBanSchedule')) {
            redirect('/admin/bots/access');
        }
    }

    public function getHeading(): string
    {
        return '';
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
                Section::make('Параметры бана')
                    ->description('Укажите базовые настройки, чтобы продолжить работу')
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ])
                    ->schema([

                        Hidden::make('bot_id'),
                        Select::make('chat_id')
                            ->label('Пользователь')
                            ->options(BotUser::select([DB::raw("CONCAT(last_name,' ', first_name, ' (', username, ')') as fullname"), 'chat_id'])->pluck('fullname','chat_id'))
                            ->searchable()
                            ->live()
                            ->disabled(auth()->user()->hasPermissionTo('Update:BotUserBanSchedule')?false:true),

                        DatePicker::make('ban_date')
                            ->label('Дата блокировки')
                            ->disabled(auth()->user()->hasPermissionTo('Update:BotUserBanSchedule')?false:true),

                        TimePicker::make('ban_time')
                            ->label('Время блокировки')
                            ->disabled(auth()->user()->hasPermissionTo('Update:BotUserBanSchedule')?false:true),

                        Checkbox::make('ban_status')
                            ->label('Статус блокировки')
                            ->disabled(auth()->user()->hasPermissionTo('Update:BotUserBanSchedule')?false:true),

                        Checkbox::make('run_status')
                            ->label('Статус удаления')
                            ->disabled(auth()->user()->hasPermissionTo('Update:BotUserBanSchedule')?false:true),

                        //
                    ]),
                Actions::make([
                    Action::make('Сохранить')
                        ->action(function () {
                            $data = $this->form->getState();

                            if ($this->id>0) {
                                BotUserBanSchedule::where('id', $this->id)->update($data);
                            } else {
                                BotUserBanSchedule::create($data);
                            }

                            Notification::make()
                                ->title('Данные успешно сохранены!')
                                ->success()
                                ->send();

                            if ($this->id>0) {
                                return redirect('/admin/bots/'.$this->bot_id.'/telegram-ban-schedules');
                            } else {
                                return redirect('/admin/bots/' . $this->bot_id . '/' . $this->id . '/telegram-ban-schedule-admin');
                            }
                        })
                        ->visible(auth()->user()->hasPermissionTo('Create:BotUserBanSchedule')),
                    Action::make('Cancel')
                        ->action(function () {
                            return redirect('/admin/bots/'.$this->bot_id.'/telegram-ban-schedules');
                        })
                        ->label('Отменить и вернуться назад')
                ]),
            ])->statePath('data');
    }


}
