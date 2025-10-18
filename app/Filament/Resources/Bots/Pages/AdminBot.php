<?php
namespace App\Filament\Resources\Bots\Pages;

use Illuminate\Support\HtmlString;

use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Components\Utilities\Set;

use App\Filament\Resources\Bots\BotResource;

use App\Actions\Core\Telegram\TelegramWebhookInfo;
use App\Actions\Core\Telegram\TelegramWebhookMake;

use App\Models\Core\Bot;

class AdminBot extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = BotResource::class;

    protected string $view = 'filament.resources.bot-resource.pages.admin-bot';

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
        $telegramWebhookInfo = new TelegramWebhookInfo();
        $telegramWebhookMake = new TelegramWebhookMake();

        $this->id = $record;

        $data = ($record>0?Bot::find($record)->toArray():[]);
        $this->name = ($record > 0?$data['name']:'Новый бот');

        if ($record > 0) {
            $webhook_address = $telegramWebhookMake->handle($record, $data['telegram_webhook']);
            $data['telegram_webhook'] = $telegramWebhookInfo->handle($data['telegram_token'], $webhook_address);
        }

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
                        TextInput::make('message_worktime_after_minutes')
                            ->label('Время ответа техподдержки до отправки автосообщения бизнес-ботом')
                            ->maxLength(255),
                        TextInput::make('business_bot_delay_after_bot_sent_message_in_minutes')
                            ->label('Не отправлять сообщение в бизнес-бот после ответа бота (в минутах)')
                            ->maxLength(255),
                        TextInput::make('business_bot_delay_after_operator_sent_message_in_minutes')
                            ->label('Не отправлять сообщение в бизнес-бот после ответа оператора (в минутах)')
                            ->maxLength(255),
                    ]),
                Section::make('Статус бота в Telegram')
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                        '2xl' => 1
                    ])->schema([
                        Textarea::make('telegram_webhook')
                            ->readOnly()
                            ->extraInputAttributes(['readonly' => true])
                    ]),
                Actions::make([
                    Action::make('Сохранить')
                        ->action(function () {
                            $data = $this->form->getState();

                            Notification::make()
                                ->title('Данные успешно сохранены!')
                                ->success()
                                ->send();

                            if ($this->id > 0) {
                                Bot::where('id', $this->id)->update($data);
                                return redirect('/admin/bots/'.$this->id.'/edit');
                            } else {
                                $new = Bot::create($data);
                                return redirect('/admin/bots/'.$new->id.'/edit');
                            }
                        }),
                    Action::make('webhook_view')
                        ->label('Запросить статус Webhook')
                        ->action(function (Set $set) {
                            $set('telegram_webhook', 'default value');
                        }),
                    Action::make('webhook_set')
                        ->label('Установить Webhook')
                        ->action(function () {
                            $data = $this->form->getState();
                        }),
                    Action::make('webhook_delete')
                        ->label('Удалить Webhook')
                        ->action(function () {
                            $data = $this->form->getState();
                        }),
                ])
            ])->statePath('data');
    }
}
?>
