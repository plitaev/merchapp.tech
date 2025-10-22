<?php
namespace App\Filament\Resources\Bots\Pages;

use App\Actions\Core\Telegram\TelegramSendMessage;
use App\Actions\Core\DateEnd\DateEnd;
use App\Models\Core\Pay;
use App\Models\Core\PayGuest;
use App\Models\Core\TelegramBanScheduleLogs;
use App\Models\Core\TelegramSendMessageLog;
use App\Models\Core\TelegramSendMessageSchedule;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Illuminate\Support\HtmlString;
use DB;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use Filament\Tables\Columns\Summarizers\Count;

use Filament\Tables\Columns\Summarizers\Sum;
use App\Filament\Resources\Bots\BotResource;
use App\Models\Core\Bot;
use App\Models\Core\BotMessage;
use App\Models\Core\BotUser;
use App\Models\Core\User;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;

use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

use App\Actions\Core\BotSendMessage\BotSendMessage;
use App\Actions\Core\Pay\PayCreateByPayGuest;
use Symfony\Component\Console\Input\Input;

class BotChatAdmin extends Page implements HasForms, HasInfolists
{
    use InteractsWithForms;
    use InteractsWithInfolists;

    protected static string $resource = BotResource::class;

    protected string $view = 'filament.resources.bot-resource.pages.bot-telegram-chat';


    public static ?string $label = "Телеграмм чат";
    public static ?string $navigationLabel = "Телеграмм чат";
    public static ?string $title = "Телеграмм чат";

    public ?array $data = [];

    public int $id;

    public int $bot_id;
    public string $bot_name;

    public int $count;
    public int $bot_user_id;

    public function getRecord(): ?Model
    {
        return BotUser::class;
    }

    public function getHeading(): string
    {
        return $this->bot_name;
    }

    public function getTitle(): string
    {
        return $this->bot_name;
    }

    public function mount(int $bot_id, int $id): void
    {
        $bot = Bot::select('name')->find($bot_id);
        $this->bot_name = $bot->name;

        $this->id = $id;

        $data = ($id > 0 ? BotUser::find($id)->toArray() : []);

        $this->bot_user_id = $id;

        if ($id > 0) {
            $bot_user = BotUser::select('telegram_chat_id')->find($id);
            $this->count = TelegramSendMessageLog::where('chat_id', $bot_user->telegram_chat_id)->count();
        }

        $this->form->fill($data);
    }

    protected function getForms(): array
    {
        return ['form'];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Email')
                    ->description('Введите Email, который будет привязан к аккаунту пользователя в боте (для сверки доступа)')
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                        '2xl' => 1,
                    ])
                    ->schema([
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                    ]),
                Section::make('Привязка аккаунта к бизнес-боту')
                    ->description('Поставьте галочку, если к данному аккаунту привязан бизнес-бот')
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                        '2xl' => 1,
                    ])
                    ->schema([
                        Forms\Components\Checkbox::make('business_bot_account')
                            ->label('Аккаунт привязан к бизнес-боту')

                    ]),
                Section::make('Автоплатеж')
                    ->description('Включайте и отключайте рекуррентные платежи у пользователя')
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                        '2xl' => 1,
                    ])
                    ->schema([
                        Forms\Components\Checkbox::make('recurrent')
                            ->label('Автоплатеж включен')
                    ]),
                Section::make('Статистика')
                    ->description(new HtmlString("<b><a href='/admin/bots/{$this->bot_id}/{$this->bot_user_id}/telegram-send-message-logs'>Сообщения от бота: ".$this->count."️ ⬇</a> </b>"))
                    ->columns([
                        'sm' => 4,
                        'md' => 4,
                        'lg' => 4,
                        'xl' => 4,
                        '2xl' => 4,
                    ])
                    ->schema([]),
                Actions::make([
                    Action::make('Сохранить')
                        ->action(function () {
                            $data = $this->form->getState();

                            if ($this->id > 0) {
                                BotUser::where('id', $this->id)->update($data);
                                $output_id = $this->id;

                                $payCreateByPayGuest = new PayCreateByPayGuest();

                                $bot_user = BotUser::find($this->id);
                                $payCreateByPayGuest->handle($bot_user, $data['email']);
                            }

                            Notification::make()
                                ->title('Данные успешно сохранены!')
                                ->success()
                                ->send();

                            return redirect('/admin/bots/' . $this->bot_id . '/chats');
                        }),
                    Action::make('send_message')
                        ->label('Отправить сообщение')
                        ->form([
                            Select::make('bot_message_id')
                                ->label('Сообщение')
                                ->required()
                                ->options(
                                    BotMessage::query()->pluck('name', 'id')
                                )
                                ->searchable()
                        ])
                        ->action(function (array $data): void {

                            $bot_message = BotMessage::with('bot_message_appointment')->where('id', $data['bot_message_id'])->first();
                            if ($bot_message) {
                                $botSendMessage = new BotSendMessage();
                                $bot_user = BotUser::find($this->id);
                                $botSendMessage->handle($bot_user, $bot_message->bot_message_appointment->alias);
                            }

                        }),
                    Action::make('update_user')
                        ->label('Сменить пользователя')
                        ->form([
                            TextInput::make('day_add')
                                ->label('Количество дней')
                                ->required()
                        ])
                        ->action(function (array $data): void {
                            $pays = Pay::where('status',1)->where('bot_user_id', $this->bot_user_id)->get();

                            if($pays->count() > 0){
                                foreach ($pays as $pay){
                                    $date_add = Carbon::parse($pay['created_at'])->subDays($data['day_add'])->format('Y-m-d');

                                    if($date_add > now()) {
    
                                        PayGuest::create([
                                            'product_id' => $pay->product_id,
                                            'email' => $pay->email,
                                            'price' => $pay->price,
                                            'days' => $pay->days,
                                            'gift' => $pay->gift,
                                            'status' => $pay->status,
                                            'recurrent' => $pay->recurrent,
                                            'recurrent_status' => $pay->recurrent_status,
                                            'bot_user_id' => $pay->bot_user_id,
                                            'created_at' => $pay->created_at,
                                            'updated_at' => $pay->updated_at
                                        ]);
    
                                        Pay::update('status', 0)->update('created_at', date('Y-m-d H:i:s', $date_add))->where('id', $pay->id);
                                    }
                                }

                                $dateEnd = new DateEnd();
                                $bot_user = BotUser::select('id', 'bot_id')->where('id', $this->bot_user_id)->where('bot_id',$this->bot_id)->first();
                                $dateEnd->handle($bot_user, 'd.m.Y');

                                BotUser::where('id', $this->bot_user_id)->update(['email' => NULL, 'ban'=> 1]);

                                Notification::make()
                                    ->title('Пользователь забанен!')
                                    ->success()
                                    ->send();
                            }
                        }),
                    Action::make('Cancel')
                        ->action(function () {
                            return redirect('/admin/bots/' . $this->bot_id . '/chats');
                        })
                        ->label('Отменить и вернуться назад')
                ]),

            ])->statePath('data');
    }

}

