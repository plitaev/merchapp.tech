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
use Filament\Actions\EditAction;

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



class BotMessageListeners extends Page implements HasTable, HasForms,  HasInfolists
{
    use InteractsWithInfolists;
    use InteractsWithTable;
    use InteractsWithForms;

    protected static string $resource = BotResource::class;

    protected string $view = 'filament.resources.bot-resource.pages.bot-message-listeners';

    public int $id;

    public int $bot_id;
    public int $bot_message_id;

    public string $bot_name;

    public ?array $data_bot_message_link_listener = [];


    protected static ?string $model = Listener::class;

    public static ?string $label = "Ожидание";
    public static ?string $navigationLabel = "Ожидание";
    public static ?string $title = "Ожидание";
    public ?array $data = [];

    public function getRecord(): ?Model
    {
        return BotMessageListener::class;
    }

    public function getHeading(): string
    {

        return $this->bot_name;

    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getForms(): array
    {
        return ['form_bot_message_link_listener'];
    }


    public function mount(int $bot_id, int $id): void
    {
        $bot = Bot::select('name')->find($bot_id);
        $this->bot_name = $bot->name;

        $this->bot_id = $bot_id;
        $this->id = $id;

        $this->form_bot_message_link_listener->fill([]);

    }

    public function getTitle(): string
    {
        return "Ожидание";
    }

    public function form_bot_message_link_listener(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Выберите параметр')
                    ->description('')
                    ->schema([
                        Hidden::make('bot_message_id'),
                        Select::make('listener_id')
                            ->label('Параметр')
                            ->required()
                            ->validationMessages([
                                'required' => 'Обязательно выберите значение из списка',
                            ])
                            ->options(
                                Listener::query()->pluck('name', 'id')
                            )
                            ->searchable()
                            ->disabled(auth()->user()->hasPermissionTo('Update:BotMessageListener')?false:true),

                    ]),
                Actions::make([
                    Action::make('Сохранить')
                        ->action(function () {
                            $formdata = $this->form_bot_message_link_listener->getState();

                            BotMessageListener::upsert(
                                ['bot_message_id' => $this->id, 'listener_id' => $formdata['listener_id']],
                                ['listener_id', 'bot_message_id'],
                                ['updated_at' => now()]
                            );

                            $this->dispatch('close-modal', id: 'add-page-modal');
                        })
                        ->visible(auth()->user()->hasPermissionTo('Create:BotMessageListener')),

                    Action::make('Отмена')
                        ->action(function () {
                            $this->dispatch('close-modal', id: 'add-page-modal');
                        })
                ])
            ])->statePath('data_bot_message_link_listener');
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('updated_at', 'desc')
            ->query(
                BotMessageListener::with('bot_message')->with('listener')
                    ->where('bot_message_id', $this->id)

            )
            ->persistSearchInSession()
            ->columns([
                TextColumn::make('listener.name')
                    ->label('Название')
                    ->searchable(),
                TextColumn::make('listener.alias')
                    ->label('Alias')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Дата/время отправки')
            ])
            ->recordActions([
//                ViewAction::make()->url(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/listener-admin")
//                    ->visible(!auth()->user()->can('Create:Listener')),
                DeleteAction::make()
                    ->visible(!auth()->user()->can('Delete:BotMessageListener')),


            ])

            ->filters([
                //
            ]);
    }

}
