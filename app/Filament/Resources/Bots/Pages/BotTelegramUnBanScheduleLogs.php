<?php
namespace App\Filament\Resources\Bots\Pages;

use App\Models\Core\BotUserBanSchedule;
use App\Models\Core\BotUserUnbanSchedule;
use App\Models\Core\TelegramSendMessageLog;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\DeleteAction;
use App\Models\Core\BotMessage;
use App\Models\Core\TelegramSendMessageSchedule;
use Illuminate\Support\HtmlString;

use App\Filament\Resources\Bots\BotResource;

use Filament\Forms;
use Filament\Forms\Components;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;

use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

use App\Actions\Core\Sending\SendingSave;

use App\Models\Core\Bot;
use App\Models\Core\Sending;
use App\Models\Core\SendingAppointment;
use App\Models\Core\SendingButton;
use App\Models\Core\SendingListener;
use App\Models\Core\SendingType;
use App\Models\Core\BotUser;
use App\Models\Core\Funnel;
use App\Models\Core\FunnelCondition;
use App\Models\Core\FunnelConditionTrigger;
use App\Models\Core\Listener;
use Illuminate\Support\Facades\Auth;

class BotTelegramUnBanScheduleLogs extends Page implements HasTable, HasInfolists
{
    use InteractsWithInfolists;
    use InteractsWithTable;

    protected static string $resource = BotResource::class;

    protected string $view = 'filament.resources.bot-resource.pages.bot-telegram-unban-schedule-logs';

    public int $id;

    public int $bot_id;
    public int $bot_message_id;

    public int $b_user_id;
    public int $bot_user_id;
    public int $bot_user;

    public string $bot_name;


    protected static ?string $model = Sending::class;

    public static ?string $label = "Рассылка";
    public static ?string $navigationLabel = "Рассылка";
    public static ?string $title = "Рассылка";
    public ?array $data = [];

    public function getRecord(): ?Model
    {
        return Sending::class;
    }

    public function getHeading(): string
    {

        return $this->bot_name;

    }

    protected function getHeaderActions(): array
    {
        return [];
    }


    public function mount(int $bot_id, int $bot_user_id): void
    {
        $bot = Bot::select('name')->find($bot_id);
        $this->bot_name = $bot->name;

        $this->bot_id = $bot_id;
        $this->bot_user_id = $bot_user_id;

        $bot_user = BotUser::select('telegram_chat_id')->find($this->bot_user_id);
        $this->b_user_id = $bot_user->telegram_chat_id;

        if (!Auth::user()->hasPermissionTo('View:TelegramUnbanSchedule')) {
            redirect('/admin/bots/access');
        }
    }

    public function getTitle(): string
    {
        return "Разбан пользователя";
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('updated_at', 'desc')
            ->query(
                BotUserUnBanSchedule::with('bot_user', 'run_status_name')
                    ->whereHas('bot_user', function ($query) {
                        $query->where('bot_id', $this->bot_id);
                        $query->where('bot_user_id', $this->bot_user_id);

                    })

            )
            ->persistSearchInSession()

            ->columns([
                TextColumn::make('unban_datetime')
                    ->label('Дата и время бана')
                    ->dateTime('d.m.Y H:i:s'),
                TextColumn::make('run_status_name.name')
                    ->label('Статус')
                    ->color(fn (string $state): string => match ($state) {
                        'Завершено' => 'success',
                        'Ожидание' => 'danger',
                        'Зарезервировано' => 'info',
                        'Отменено' => 'info',
                    })
            ])

            ->filters([
                //
            ]);
    }

}
