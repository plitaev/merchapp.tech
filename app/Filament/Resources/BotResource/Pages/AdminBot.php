<?php
namespace App\Filament\Resources\BotResource\Pages;

use App\Filament\Resources\BotResource;
use App\Filament\Resources\BotResource\RelationManagers;
use App\Livewire\Agency\Inbox\ActiveTasksTable;
use App\Models\Core\Bot;
use App\Models\Core\TelegramSupergroupLinkBot\TelegramSupergroupLinkBot;
use Filament\Forms;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;


class AdminBot extends Page implements HasForms
{
    use InteractsWithForms;


    protected static string $resource = BotResource::class;

    protected static string $view = 'filament.resources.bot-resource.pages.admin-bot';

    protected static ?string $model = Bot::class;

    public static ?string $navigationLabel = "";
    public static ?string $title = "Настройки бота";
    public ?array $data = [];

    public $record;

    public int $id;

    public string $name;

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
        return ['form'];
    }

    public function mount(int $record): void
    {
        $this->id = $record;

        if ($record > 0) {
            $bot = Bot::find($record)->toArray();
            $this->name = $bot['name'];
        } else {
            $bot = [];
            $this->name = 'Новый бот';
        }

        $data = ($record>0?Bot::find($record)->toArray():[]);
        $this->form->fill($data);
    }

    public function getHeading(): string
    {
        return $this->name;
    }

    public function getTitle(): string
    {
        return $this->name;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
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
                        Forms\Components\TextInput::make('name')
                            ->label('Название (Только в панели администратора)')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('alias')
                            ->label('Username в Telegram')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('telegram_token')
                            ->label('Telegram-токен (из BotFather)')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('telegram_webhook')
                            ->label('Адрес вебхука Telegram')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('message_worktime_after_minutes')
                            ->label('Время ответа техподдержки до отправки автосообщения бизнес-ботом')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('business_bot_delay_after_bot_sent_message_in_minutes')
                            ->label('Не отправлять сообщение в бизнес-бот после ответа бота (в минутах)')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('business_bot_delay_after_operator_sent_message_in_minutes')
                            ->label('Не отправлять сообщение в бизнес-бот после ответа оператора (в минутах)')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('yookassa_provider_token')
                            ->label('Токен Юкассы')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('yookassa_shop_id')
                            ->label('Юкасса ShopID')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('yookassa_shop_secret')
                            ->label('Юкасса ShopSecret')
                            ->maxLength(255),
                    ]),
                Actions::make([
                    Action::make('Сохранить')
                        ->action(function () {
                            $data = $this->form->getState();

                            if ($this->id > 0) {
                                Bot::where('id', $this->id)->update($data);
                                return redirect('/admin/bots/'.$this->id.'/edit');
                            } else {
                                $new = Bot::create($data);
                                return redirect('/admin/bots/'.$new->id.'/edit');
                            }

                            Notification::make()
                                ->title('Данные успешно сохранены!')
                                ->success()
                                ->send();
                        }),
                ])
            ])->statePath('data');
    }

}
