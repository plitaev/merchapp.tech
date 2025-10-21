<?php
namespace App\Filament\Resources\Bots\Pages;

use App\Models\Core\BotMessageListener;
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

use App\Actions\Core\Listener\ListenerSave;

use App\Models\Core\Bot;
use App\Models\Core\Listener;
use App\Models\Core\ListenerAppointment;
use App\Models\Core\ListenerButton;
use App\Models\Core\ListenerListener;
use App\Models\Core\ListenerType;
use App\Models\Core\BotUser;
use App\Models\Core\Funnel;
use App\Models\Core\FunnelCondition;
use App\Models\Core\FunnelConditionTrigger;
use App\Models\Core\BotTeleramListener;


class BotMessageListenerLogs extends Page implements HasTable, HasInfolists
{
    use InteractsWithInfolists;
    use InteractsWithTable;

    protected static string $resource = BotResource::class;

    protected string $view = 'filament.resources.bot-resource.pages.bot-message-listener-logs';

    public int $id;

    public int $bot_id;
    public int $bot_message_id;

    public string $bot_name;


    protected static ?string $model = Listener::class;

    public static ?string $label = "Сообщение";
    public static ?string $navigationLabel = "Сообщение";
    public static ?string $title = "Сообщение";
    public ?array $data = [];

    public function getRecord(): ?Model
    {
        return BotTelegramListener::class;
    }

    public function getHeading(): string
    {

        return $this->bot_name;

    }

    protected function getHeaderActions(): array
    {
        return [];
    }


    public function mount(int $bot_id, int $id): void
    {
        $bot = Bot::select('name')->find($bot_id);
        $this->bot_name = $bot->name;

        $this->bot_id = $bot_id;
        $this->id = $id;

    }

    public function getTitle(): string
    {
        return "Сообщения пользователя";
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('updated_at', 'desc')
            ->query(
                BotMessageListener::with('bot_message')->with('listener')
                    ->where('listener_id', $this->id)

            )
            ->columns([
                TextColumn::make('bot_message.name')
                    ->label('Название')
                    ->searchable(),
                TextColumn::make('bot_message.text')
                    ->label('Текст')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Дата/время отправки')
            ])

            ->filters([
                //
            ]);
    }

}
