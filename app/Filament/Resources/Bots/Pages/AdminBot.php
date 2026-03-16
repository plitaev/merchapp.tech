<?php
namespace App\Filament\Resources\Bots\Pages;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

use App\Models\Core\Bot;
use App\Models\Core\BotBranch;
use App\Models\Core\BotMessage;
use App\Models\Core\BotMessageAppointment;
use App\Models\Core\BotMessageButton;
use App\Models\Core\BotTemplateMessage;
use App\Models\Core\BotTemplateMessageButton;

use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Toggle;
use Filament\Actions\ViewAction;

use Filament\Forms\Components\TimePicker;
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

use App\Actions\Core\Max\MaxQuery;
use App\Actions\Core\Telegram\TelegramDeleteWebhook;
use App\Actions\Core\Telegram\TelegramWebhookMake;
use App\Actions\Core\Telegram\TelegramWebhookInfo;
use App\Actions\Core\Telegram\TelegramSetWebhook;

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

    public string $webhook;
    public string $max_webhook;

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
        $maxQuery = new MaxQuery();

        $telegramWebhookInfo = new TelegramWebhookInfo();
        $telegramWebhookMake = new TelegramWebhookMake();
        $this->id = $record;

        $data = ($record>0?Bot::find($record)->toArray():[]);
        $this->name = ($record > 0?$data['name']:'Новый бот');
        $this->webhook = ($record > 0?$data['name']:'Новый бот');
        $this->max_webhook = ($record > 0?$data['name']:'Новый бот');


        if ($record > 0) {
            $webhook_address = $telegramWebhookMake->handle($record, $data['telegram_webhook']);
            $data['telegram_webhook_status'] = $telegramWebhookInfo->handle($data['telegram_token'], $webhook_address);

            if ($data['max_token']) {
                $bot = Bot::find($record);
                $data['max_webhook_status'] = $maxQuery->handle($bot, 'GET', 'subscriptions',[], false);
            } else {
                $data['max_webhook_status'] = 'Не указан токен Max. Для интеграции с Max введите токен бота, нажмите Сохранить, и вернитесь к этой странице.';
            }
        }

        $this->form->fill($data);

//        if (!Auth::user()->hasPermissionTo('View:Bot')) {
//            redirect('/admin/bots/access');
//        }
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
                            ->maxLength(255)
                            ->disabled(auth()->user()->hasPermissionTo('Update:Bot')?false:true),
                        TextInput::make('alias')
                            ->label('Username в Telegram')
                            ->required()
                            ->maxLength(255)
                            ->disabled(auth()->user()->hasPermissionTo('Update:Bot')?false:true),
                        TextInput::make('telegram_token')
                            ->label('Telegram-токен (из BotFather)')
                            ->required()
                            ->maxLength(255)
                            ->disabled(auth()->user()->hasPermissionTo('Update:Bot')?false:true),
                        TextInput::make('telegram_webhook')
                            ->label('Адрес вебхука Telegram')
                            ->required()
                            ->maxLength(255)
                            ->disabled(auth()->user()->hasPermissionTo('Update:Bot')?false:true),
                        TextInput::make('max_token')
                            ->label('Max-токен (из Max для партнёров)')

                            ->maxLength(255)
                            ->disabled(auth()->user()->hasPermissionTo('Update:Bot')?false:true),
                        TextInput::make('max_webhook')
                            ->label('Адрес вебхука Max')

                            ->maxLength(255)
                            ->disabled(auth()->user()->hasPermissionTo('Update:Bot')?false:true),
                        TimePicker::make('ban_time')
                            ->label('Время бана')
                            ->disabled(auth()->user()->hasPermissionTo('Update:Bot')?false:true),
                        TimePicker::make('recurrent_time')
                            ->label('Время списания рекуррента')
                            ->disabled(auth()->user()->hasPermissionTo('Update:Bot')?false:true),
                    ]),
                Section::make('Бизнес-бот')
                    ->description('Параметры бота техподдержки, подключенного к бизнес-аккаунту Telegram')
                    ->columns([
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ])
                    ->schema([
                        TextInput::make('message_worktime_after_minutes')
                            ->label('Время ответа техподдержки до отправки автосообщения бизнес-ботом')
                            ->maxLength(255)
                            ->disabled(auth()->user()->hasPermissionTo('Update:Bot')?false:true),
                        TextInput::make('business_bot_delay_after_bot_sent_message_in_minutes')
                            ->label('Не отправлять сообщение в бизнес-бот после ответа бота (в минутах)')
                            ->maxLength(255)
                            ->disabled(auth()->user()->hasPermissionTo('Update:Bot')?false:true),
                        TextInput::make('business_bot_delay_after_operator_sent_message_in_minutes')
                            ->label('Не отправлять сообщение в бизнес-бот после ответа оператора (в минутах)')
                            ->maxLength(255)
                            ->disabled(auth()->user()->hasPermissionTo('Update:Bot')?false:true)
                    ]),

                Actions::make([
                    Action::make('Сохранить')
                        ->action(function () {
                            $data = $this->form->getState();

                            Notification::make()
                                ->title('Данные успешно сохранены!')
                                ->success()
                                ->send();

                            unset($data['telegram_webhook_status']);

                            if ($this->id > 0) {
                                Bot::where('id', $this->id)->update($data);
                                return redirect('/admin/bots/'.$this->id.'/edit');
                            } else {
                                $new = Bot::create($data);


                                BotBranch::create(
                                    [
                                        'bot_id' => $new->id,
                                        'bot_branch_type' => 1,
                                        'name' => 'Главная ветка',
                                        'alias' => 'BRANCH_MAIN',
                                        'hash' => 'BRANCH_MAIN',
                                        'datetime_start' => date('Y-m-d H:i:s', time()),
                                        'datetime_end' => date('2100-01-01 00:00:00'),
                                    ]
                                );

                                Notification::make()
                                    ->title('Данные успешно сохранены!')
                                    ->success()
                                    ->send();

                                return redirect('/admin/bots/'.$new->id.'/edit');
                            }
                        })
                        ->visible(auth()->user()->hasPermissionTo('Create:Bot')),
                    Action::make('load_copy')
                        ->label('Загрузить из шаблона')
                        ->action(function (Set $set) {

                            $botTemplateMessages = BotTemplateMessage::get();

                            if($botTemplateMessages) {
                                foreach ($botTemplateMessages as $templateMessage) {

                                    $newBotTemplateMessages = new BotMessage();

                                    $bot_message_appointment = BotMessageAppointment::select('id')->where('alias', $templateMessage->bot_message_appointment_alias)->first();

                                    if ($bot_message_appointment) {
                                        $bot_message_appointment_id = $bot_message_appointment->id;
                                    } else {
                                        $bot_message_appointment_id = NULL;
                                    }

                                    $newBotTemplateMessages->bot_id = $this->id;
                                    $newBotTemplateMessages->bot_branch_id = $templateMessage['bot_template_branch_id'];
                                    $newBotTemplateMessages->bot_message_type_id = $templateMessage['bot_message_type_id'];
                                    $newBotTemplateMessages->bot_message_appointment_id = $bot_message_appointment_id;
                                    $newBotTemplateMessages->funnel_id = $templateMessage['funnel_id'];
                                    $newBotTemplateMessages->funnel_condition_id = $templateMessage['funnel_condition_id'];
                                    $newBotTemplateMessages->funnel_condition_trigger_id = $templateMessage['funnel_condition_trigger_id'];
                                    $newBotTemplateMessages->funnel_days = $templateMessage['funnel_days'];
                                    $newBotTemplateMessages->funnel_hours = $templateMessage['funnel_hours'];
                                    $newBotTemplateMessages->funnel_minutes = $templateMessage['funnel_minutes'];
                                    $newBotTemplateMessages->funnel_product_id = $templateMessage['funnel_product_id'];
                                    $newBotTemplateMessages->name = $templateMessage['name'];
                                    $newBotTemplateMessages->text = $templateMessage['text'];
                                    $newBotTemplateMessages->delete_through = $templateMessage['delete_through'];
                                    $newBotTemplateMessages->delete_through_hours = $templateMessage['delete_through_hours'];
                                    $newBotTemplateMessages->delete_through_minutes = $templateMessage['delete_through_minutes'];
                                    $newBotTemplateMessages->delete_keyboard_through = $templateMessage['delete_keyboard_through'];
                                    $newBotTemplateMessages->delete_keyboard_through_days = $templateMessage['delete_keyboard_through_days'];
                                    $newBotTemplateMessages->delete_keyboard_through_hours = $templateMessage['delete_keyboard_through_hours'];
                                    $newBotTemplateMessages->delete_keyboard_through_minutes = $templateMessage['delete_keyboard_through_minutes'];
                                    $newBotTemplateMessages->pause_after_message = $templateMessage['pause_after_message'];

                                    $newBotTemplateMessages->bot_branch_id = $templateMessage['bot_template_branch_id'];

                                    $newBotTemplateMessages->save();

                                    $botTemplateMessageButtons = BotTemplateMessageButton::where('bot_template_message_id', $templateMessage['id'])->get();


                                    foreach ($botTemplateMessageButtons as $botTemplateMessageButton) {

                                        $newBotTemplateMessageButtons = new BotMessageButton();

                                        $newBotTemplateMessageButtons->bot_message_id = $newBotTemplateMessages->id;
                                        $newBotTemplateMessageButtons->bot_message_button_type_id = $botTemplateMessageButton['bot_message_button_type_id'];
                                        $newBotTemplateMessageButtons->pay_system_id = $botTemplateMessageButton['pay_system_id'];
                                        $newBotTemplateMessageButtons->product_id = $botTemplateMessageButton['product_id'];
                                        $newBotTemplateMessageButtons->name = $botTemplateMessageButton['name'];
                                        $newBotTemplateMessageButtons->url = $botTemplateMessageButton['url'];
                                        $newBotTemplateMessageButtons->bot_message_callback_id = $botTemplateMessageButton['bot_message_callback_id'];
                                        $newBotTemplateMessageButtons->callback = $botTemplateMessageButton['callback'];
                                        $newBotTemplateMessageButtons->tracking = $botTemplateMessageButton['tracking'];
                                        $newBotTemplateMessageButtons->pos = $botTemplateMessageButton['pos'];
                                        $newBotTemplateMessageButtons->save();
                                    }

                                }
                            }


                            Notification::make()
                                ->title('Данные успешно загружены!')
                                ->success()
                                ->send();
                        })
                        ->visible(auth()->user()->hasPermissionTo('Update:Bot')),

                    Action::make('Cancel')
                        ->action(function () {
                            return redirect('/admin/bots');
                        })
                        ->label('Отменить и вернуться назад')

                ]),

                Section::make('Статус бота в Telegram')
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                        '2xl' => 1
                    ])->schema([
                        Textarea::make('telegram_webhook_status')
                            ->label('Ответ Telegram')
                            ->readOnly()
                            ->extraInputAttributes(['readonly' => true])
                    ]),
                Actions::make([

                    Action::make('webhook_view')
                        ->label('Запросить статус Webhook Telegram')
                        ->action(function (Set $set) {
                            $telegramWebhookInfo = new TelegramWebhookInfo();
                            $telegramWebhookMake = new TelegramWebhookMake();

                            $formdata = $this->form->getState();

                            $webhook_address = $telegramWebhookMake->handle($this->id, $formdata['telegram_webhook']);
                            $status = $telegramWebhookInfo->handle($formdata['telegram_token'], $webhook_address);

                            if (is_callable($set)) {
                                $set('telegram_webhook_status', $status);
                            }
                        })
                        ->visible(auth()->user()->hasPermissionTo('Update:Bot')),

                    Action::make('webhook_set')
                        ->label('Установить Webhook Telegram')
                        ->action(function (Set $set) {
                            $telegramSetWebhook = new TelegramSetWebhook();
                            $telegramWebhookMake = new TelegramWebhookMake();

                            $formdata = $this->form->getState();

                            $webhook_address = $telegramWebhookMake->handle($this->id, $formdata['telegram_webhook']);
                            $status = $telegramSetWebhook->handle($this->id, $formdata['telegram_token'], $formdata['telegram_webhook']);

                            if (is_callable($set)) {
                                $set('telegram_webhook_status', $status);
                            }
                        })
                        ->visible(auth()->user()->hasPermissionTo('Update:Bot')),

                    Action::make('webhook_delete')
                        ->label('Удалить Webhook Telegram')
                        ->action(function (Set $set) {
                            $telegramDeleteWebhook = new TelegramDeleteWebhook();
                            $telegramWebhookMake = new TelegramWebhookMake();

                            $formdata = $this->form->getState();

                            $webhook_address = $telegramWebhookMake->handle($this->id, $formdata['telegram_webhook']);
                            $status = $telegramDeleteWebhook->handle($formdata['telegram_token'], $webhook_address);

                            if (is_callable($set)) {
                                $set('telegram_webhook_status', $status);
                            }
                        })
                        ->visible(auth()->user()->hasPermissionTo('Delete:Bot')),
                ]),
                Section::make('Статус бота в Max')
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                        '2xl' => 1
                    ])->schema([
                        Textarea::make('max_webhook_status')
                            ->label('Ответ Max')
                            ->readOnly()
                            ->extraInputAttributes(['readonly' => true])
                    ]),

                Actions::make([

                    Action::make('max_webhook_view')
                        ->label('Запросить статус Webhook Max')
                        ->action(function (Set $set) {
                            $telegramWebhookInfo = new TelegramWebhookInfo();
                            $telegramWebhookMake = new TelegramWebhookMake();

                            $formdata = $this->form->getState();

                            $webhook_address = $telegramWebhookMake->handle($this->id, $formdata['telegram_webhook']);
                            $status = $telegramWebhookInfo->handle($formdata['telegram_token'], $webhook_address);

                            if (is_callable($set)) {
                                $set('telegram_webhook_status', $status);
                            }
                        })
                        ->visible(auth()->user()->hasPermissionTo('Update:Bot')),

                    Action::make('max_webhook_set')
                        ->label('Установить Webhook Max')
                        ->action(function (Set $set) {
                            $telegramSetWebhook = new TelegramSetWebhook();
                            $telegramWebhookMake = new TelegramWebhookMake();

                            $formdata = $this->form->getState();

                            $webhook_address = $telegramWebhookMake->handle($this->id, $formdata['telegram_webhook']);
                            $status = $telegramSetWebhook->handle($this->id, $formdata['telegram_token'], $formdata['telegram_webhook']);

                            if (is_callable($set)) {
                                $set('telegram_webhook_status', $status);
                            }
                        })
                        ->visible(auth()->user()->hasPermissionTo('Update:Bot')),

                    Action::make('max_webhook_delete')
                        ->label('Удалить Webhook Max')
                        ->action(function (Set $set) {
                            $telegramDeleteWebhook = new TelegramDeleteWebhook();
                            $telegramWebhookMake = new TelegramWebhookMake();

                            $formdata = $this->form->getState();

                            $webhook_address = $telegramWebhookMake->handle($this->id, $formdata['telegram_webhook']);
                            $status = $telegramDeleteWebhook->handle($formdata['telegram_token'], $webhook_address);

                            if (is_callable($set)) {
                                $set('telegram_webhook_status', $status);
                            }
                        })
                        ->visible(auth()->user()->hasPermissionTo('Delete:Bot')),
                ]),

            ])->statePath('data');
    }

}
?>
