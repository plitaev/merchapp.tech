<?php
namespace App\Filament\Resources\Bots\Pages;

use App\Actions\Core\Telegram\TelegramSendMessage;
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

        $this->count = TelegramSendMessageLog::query()
            ->with('bot_message:bot_id')
            ->selectRaw('COUNT(bot_message_id) as total_rows')
            ->whereHas('bot_message', function ($query)  {
                $query->where('bot_id', $this->bot_id);
            })->count();
        $this->bot_user_id = auth()->user()->id;

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
                Section::make('Сообщения от бота (кол-во записей сообщений в БД)')
                    ->description(new HtmlString("<b><a href='/admin/bot-users/{$this->bot_user_id}/telegram-send-message-logs'>".$this->count."<a></b>"))
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
                    Action::make('Cancel')
                        ->action(function () {
                            return redirect('/admin/bots/' . $this->bot_id . '/chats');
                        })
                        ->label('Отменить и вернуться назад')
                ]),

            ])->statePath('data');
    }

}

