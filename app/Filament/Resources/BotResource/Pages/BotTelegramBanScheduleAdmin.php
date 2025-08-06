<?php
namespace App\Filament\Resources\BotResource\Pages;

use App\Actions\Core\TelegramBanSchedule\TelegramBanScheduleCreateByEmail;
use App\Filament\Resources\BotResource;
use App\Models\Core\Bot;
use App\Models\Core\BotUser;
use App\Models\Core\BotUserBanSchedule;
use Filament\Forms;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;

class BotTelegramBanScheduleAdmin extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = BotResource::class;

    protected static string $view = 'filament.resources.bot-resource.pages.bot-telegram-ban-schedule-admin';

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
    }

    public function getHeading(): string
    {
        if ($this->id > 0) {
            return "Редактировать бан";
        } else {
            return "Добавить бан";
        }
    }

    public function getTitle(): string
    {
        return $this->bot_name;
    }

    protected function getForms(): array
    {
        return ['form'];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
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

                Forms\Components\Hidden::make('bot_id'),
                Forms\Components\Select::make('chat_id')
                    ->label('Пользователь')
                    ->options(BotUser::select([\DB::raw("CONCAT(last_name,' ', first_name, ' (', username, ')') as fullname"), 'chat_id'])->pluck('fullname','chat_id'))
                    ->searchable()
                    ->live(),
                DatePicker::make('ban_date')
                    ->label('Дата блокировки'),
                TimePicker::make('ban_time')
                    ->label('Время блокировки'),
                Forms\Components\Checkbox::make('ban_status')
                    ->label('Статус блокировки'),
                Forms\Components\Checkbox::make('run_status')
                    ->label('Статус удаления'),
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
                      }),
                  Action::make('Cancel')
                      ->action(function () {
                          return redirect('/admin/bots/'.$this->bot_id.'/telegram-ban-schedules');
                      })
                      ->label('Отменить и вернуться назад')
              ]),
            ])->statePath('data');
    }
}
