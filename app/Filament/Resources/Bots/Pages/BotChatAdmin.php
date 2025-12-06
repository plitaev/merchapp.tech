<?php
namespace App\Filament\Resources\Bots\Pages;

use App\Actions\Core\Telegram\TelegramSendMessage;
use App\Models\Core\BotAdminLog;
use App\Models\Core\BotUserBanSchedule;
use App\Models\Core\BotUserRecurrentSchedule;
use App\Models\Core\BotUserUnbanSchedule;
use App\Models\Core\Pay;
use App\Models\Core\PayGuest;
use App\Models\Core\Product;
use App\Models\Core\TelegramBanScheduleErrorLogs;
use App\Models\Core\TelegramUnbanScheduleErrorLog;
use App\Models\Core\TelegramChatMemberErrorLog;
use App\Models\Core\TelegramSendMessageErrorLog;
use App\Models\Core\TelegramBanScheduleLogs;
use App\Models\Core\TelegramSendMessageLog;
use App\Models\Core\TelegramSendMessageSchedule;
use App\Models\Core\BotUserPrice;
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
use Illuminate\Support\Facades\Auth;
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
    public int $count_ban;
    public int $count_unban;

    public int $count_p = 0;

    public int $bot_user_prices_count = 0;

    public ?array $bot_user_prices = [];

    public ?string $bot_user_prices_individual = '';
    public ?string $bot_user_prices_standard = '';

    public ?array $products = [];

    public ?string $individual_prices = '';
    public int $count_ban_error;
    public int $count_unban_error;

    public int $count_chat_member_error;
    public int $count_send_message_error;

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
        $this->id = $id;
        $data = ($id > 0 ? BotUser::find($id)->toArray() : []);

        $this->bot_user_id = $id;

        $bot = Bot::select('name')->find($bot_id);
        $this->bot_name = $bot->name;

        $pay = Pay::where('bot_user_id', $id)
            ->where('status',0)
            ->where('recurrent',1)
            ->where('recurrent_status',0)
            ->orderBy('created_at', 'desc')
            ->limit(1)
            ->get();

        $bot_user_prices = BotUserPrice::with('product:id,name')
            ->select('product_id', 'price')
            ->where('bot_user_id', $id)
            ->get();

        $bot_user_prices_product_ids = [];

        foreach ($bot_user_prices as $bot_user_price) {
            $this->individual_prices .= "<a href='' style='display: block; margin-bottom: 10px; font-weight:bold'>".$bot_user_price->product->name . ' - ' . $bot_user_price->price."</a>";
            $bot_user_prices_product_ids[] = $bot_user_price->product_id;
        }

        if (count($bot_user_prices_product_ids) > 0) {

            $products = Product::select('id', 'name', 'price')->whereNotIn('id', $bot_user_prices_product_ids)->get();
            foreach ($products as $product) {
                $this->bot_user_prices_standard .= "<a href='' style='display: block; margin-bottom: 10px; font-weight:bold'>".$product->name . ' - ' . $product->price."</a>";;
            }

        } else {

            $products = Product::select('id', 'name', 'price')->all();
            foreach ($products as $product) {
                $this->bot_user_prices_standard .= "<a href='' style='display: block; margin-bottom: 10px; font-weight:bold'>".$product->name . ' - ' . $product->price."</a>";;
            }

        }

        /*
        $products = Product::query()->get();
        if ($products) {
            foreach ($products as $product) {
                $this->products_str .= "<a href='' style='display: block; margin-bottom: 10px; font-weight:bold'>".$product->name . ' - ' . $product->price."</a>";;
            }
        }

        $this->bot_user_prices_count = BotUserPrice::with('products')->where('bot_user_id', $this->bot_user_id)->count();
        $bot_user_prices = Product::query()->get();
        if ($this->bot_user_prices_count > 0) {
            $bot_user_prices = BotUserPrice::with('products')->where('bot_user_id', $this->bot_user_id)->get();

            foreach ($this->bot_user_prices as $bot_user_price) {
                $this->bot_user_prices_str .= "<a href='/admin/bots/{$this->bot_id}/{$this->bot_user_id}/{$bot_user_price->id}/bot-user-prices' style='display: block; margin-bottom: 10px; font-weight:bold'>".$bot_user_price->products->name . ' - ' . $bot_user_price->price." руб 🔍</a>";
                $this->bot_user_prices_str = 7;
            }
        }
        */

        if ($id > 0) {
            $bot_user = BotUser::select('telegram_chat_id')->find($id);
            $this->count = TelegramSendMessageLog::where('chat_id', $bot_user->telegram_chat_id)->count();
            $this->count_ban = BotUserBanSchedule::where('bot_user_id', $this->bot_user_id)->count();
            $this->count_unban = BotUserUnBanSchedule::where('bot_user_id', $this->bot_user_id)->count();
            $this->count_ban_error = TelegramBanScheduleErrorLogs::where('bot_user_id', $this->bot_user_id)->count();
            $this->count_unban_error = TelegramUnbanScheduleErrorLog::where('bot_user_id', $this->bot_user_id)->count();
            $this->count_chat_member_error = TelegramChatMemberErrorLog::where('bot_user_id', $this->bot_user_id)->count();
            $this->count_send_message_error = TelegramSendMessageErrorLog::where('chat_id', $bot_user->telegram_chat_id)->count();

            if($bot_user->recurrent == 0 && $pay->count() > 0){
                $this->count_p = 1;
            }

        }

        $this->form->fill($data);

        if (!Auth::user()->hasPermissionTo('View:BotUser')) {
            redirect('/admin/bots/access');
        }
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
                            ->disabled(auth()->user()->hasPermissionTo('Update:BotUser')?false:true),

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
                            ->disabled(auth()->user()->hasPermissionTo('Update:BotUser')?false:true),


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
                            ->disabled(auth()->user()->hasPermissionTo('Update:BotUser')?false:true),

                    ]),
                Section::make($this->bot_user_prices_count > 0? 'Индивидуальные цены':'Стандартные цены')
                    ->description(new HtmlString(($this->bot_user_prices_count > 0)? $this->bot_user_prices_str:$this->products_str))
                    ->columns([
                        'sm' => 4,
                        'md' => 4,
                        'lg' => 4,
                        'xl' => 4,
                        '2xl' => 4,
                    ])
                    ->schema([]),
                Section::make('Статистика')
                    ->description(new HtmlString("<a href='/admin/bots/{$this->bot_user_id}/telegram-send-message-logs' style='display: block; margin-bottom: 10px; font-weight:bold'>Сообщения от бота: {$this->count} 🔍</a><a href='/admin/bots/{$this->bot_id}/{$this->bot_user_id}/telegram-ban-schedule-logs' style='display: block; margin-top: 10px; margin-bottom: 10px; font-weight:bold'>Баны: {$this->count_ban} 🔍</a><a href='/admin/bots/{$this->bot_id}/{$this->bot_user_id}/telegram-unban-schedule-logs' style='display: block; margin-top: 10px; font-weight:bold'>Разбаны: {$this->count_unban} 🔍</a>"))
                    ->columns([
                        'sm' => 4,
                        'md' => 4,
                        'lg' => 4,
                        'xl' => 4,
                        '2xl' => 4,
                    ])
                    ->schema([]),
                Section::make('Ошибки')
                    ->description(new HtmlString("<a href='/admin/bots/{$this->bot_id}/{$this->bot_user_id}/telegram-ban-schedule-error-logs' style='display: block; margin-bottom: 10px; font-weight:bold'>Баны: {$this->count_ban_error} 🔍</a><a href='/admin/bots/{$this->bot_id}/{$this->bot_user_id}/telegram-unban-schedule-error-logs' style='display: block; margin-top: 10px; margin-bottom: 10px; font-weight:bold'>Разбаны: {$this->count_unban_error} 🔍</a><a href='/admin/bots/{$this->bot_id}/{$this->bot_user_id}/telegram-chat-member-error-logs' style='display: block; margin-top: 10px; margin-bottom: 10px; font-weight:bold'>Заявки на вступление: {$this->count_chat_member_error} 🔍</a><a href='/admin/bots/{$this->bot_id}/{$this->bot_user_id}/telegram-send-message-error-logs' style='display: block; margin-top: 10px; font-weight:bold'>Отправки сообщений: {$this->count_send_message_error} 🔍</a>"))
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
                        })
                        ->visible(fn() => auth()->user()->can('Update:BotUser')),

                    Action::make('send_message')
                        ->color('success')
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

                        })
                        ->visible(fn() => auth()->user()->can('Update:BotUser')),
                    Action::make('change_user')
                        ->label('Сменить пользователя')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function () {
                            BotAdminLog::create(['bot_user_id' =>  $this->bot_user_id, 'user_id' => auth()->id(), 'name' =>'Смена пользователя']);

                            $pays = Pay::where('status', 1)->where('bot_user_id', $this->bot_user_id)->get();
                            $bot_user = BotUser::select('id', 'bot_id', 'email')->find($this->bot_user_id);

                            if(count($pays) > 0) {
                                foreach ($pays as $pay) {

                                    if ($bot_user->email) {
                                        PayGuest::insert([
                                            'product_id' => $pay->product_id,
                                            'email' => $bot_user->email,
                                            'price' => $pay->price,
                                            'days' => $pay->days,
                                            'gift' => $pay->gift,
                                            'status' => 0,
                                            'recurrent' => $pay->recurrent,
                                            'recurrent_status' => $pay->recurrent_status,
                                            'created_at' => $pay->created_at,
                                            'updated_at' => $pay->updated_at
                                        ]);
                                    }

                                    Pay::where('id', $pay->id)->update(['status' => 0]);
                                }
                            }

                            BotUser::where('id', $this->bot_user_id)->update(['email' => NULL, 'date_end' => date('Y-m-d', time())]);

                            BotUserBanSchedule::create([
                                'bot_user_id' => $this->bot_user_id,
                                'run_status' => 0,
                                'ban_datetime' => date('Y-m-d H:i:s', time())
                            ]);

                            Notification::make()
                                ->title('Смена пользователя завершена!')
                                ->success()
                                ->send();

                            return redirect("/admin/bots/".$this->bot_id."/".$this->bot_user_id."/chat-admin");
                        })
                        ->visible(fn() => auth()->user()->can('Update:BotUser')),

                    Action::make('Списать рекуррент вручную')
                        ->label('Списать рекуррент вручную')
                        ->color('success')
                        ->requiresConfirmation()
                        ->visible(fn() => auth()->user()->can('Update:BotMessage'))
                        ->action(function () {
                            $data = $this->form->getState();
                            $botUserRecurrentSchedule = new BotUserRecurrentSchedule();

                            $botUserRecurrentSchedule->handle($data);


                            Notification::make()
                                ->title('Рекуррент успешно списан!')
                                ->success()
                                ->send();

                            return redirect('/admin/bots/' . $this->bot_id . '/chats');
                        })
                        ->visible(fn() => auth()->user()->can('Update:BotUser')),
                    Action::make('Cancel')
                        ->color('gray')
                        ->action(function () {
                            return redirect('/admin/bots/' . $this->bot_id . '/chats');
                        })
                        ->label('Отменить и вернуться назад')
                ]),

            ])->statePath('data');
    }

}

