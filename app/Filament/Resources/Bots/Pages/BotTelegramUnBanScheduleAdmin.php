<?php
namespace App\Filament\Resources\Bots\Pages;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use DB;
use Filament\Forms\Components\Checkbox;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use App\Filament\Resources\Bots\BotResource;
use App\Models\Core\Bot;
use App\Models\Core\BotUser;
use App\Models\Core\TelegramUnbanSchedule;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;

class BotTelegramUnBanScheduleAdmin extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = BotResource::class;

    protected string $view = 'filament.resources.bot-resource.pages.bot-telegram-unban-schedule-admin';

    protected static ?string $model = TelegramUnBanSchedule::class;

    public static ?string $label = "Разбан";
    public static ?string $navigationLabel = "Разбан";
    public static ?string $title = "Разбан";

    public ?array $data = [];

    public int $bot_id;
    public string $bot_name;

    public int $id;

    public string $name;

    public function getRecord(): ?Model
    {
        return TelegramBanSchedule::class;
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
            $data = TelegramUnBanSchedule::find($id)->toArray();
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
            return "Редактировать разбан";
        } else {
            return "Добавить разбан";
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

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Пользователь')
                    ->description('Укажите пользователя, чтобы продолжить работу')
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                        '2xl' => 1,
                    ])
                    ->schema([
                        Hidden::make('bot_id'),

                        Select::make('chat_id')
                            ->label('Пользователь')
                            ->options(BotUser::select([DB::raw("CONCAT(last_name,' ', first_name, ' (', username, ')') as fullname"), 'chat_id'])->pluck('fullname','chat_id'))
                            ->searchable()
                            ->live(),
                        Checkbox::make('run_status')
                            ->label('Статус удаления'),
                        Checkbox::make('unban_status')
                            ->label('Статус разблокировки'),
                        //
                        ]),

                     Actions::make([
                         Action::make('Сохранить')
                             ->action(function () {
                                 $data = $this->form->getState();

                                 if ($this->id > 0) {
                                     TelegramUnBanSchedule::where('id', $this->id)->update($data);
                                 }else{
                                     $new_unban= TelegramUnBanSchedule::create($data);
                                 }


                                 Notification::make()
                                     ->title('Данные успешно сохранены!')
                                     ->success()
                                     ->send();

                                 if ($this->id>0) {
                                     return redirect('/admin/bots/' . $this->bot_id . '/telegram-unban-schedules');
                                 }else{
                                     return redirect('/admin/bots/' . $this->bot_id .'/'.$this->id. '/telegram-unban-schedule-admin');
                                 }

                             }),
                         Action::make('Cancel')
                             ->action(function () {
                                 return redirect('/admin/bots/' . $this->bot_id . '/telegram-unban-schedules');
                             })
                             ->label('Отменить и вернуться назад')
                     ]),

                ])->statePath('data');

     }


}
