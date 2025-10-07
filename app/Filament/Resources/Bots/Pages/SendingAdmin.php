<?php
namespace App\Filament\Resources\Bots\Pages;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use App\Models\Core\BotMessage;
use Illuminate\Support\HtmlString;

use App\Filament\Resources\Bots\BotResource;

use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
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

class SendingAdmin extends Page implements HasForms, HasInfolists
{
    use InteractsWithForms;
    use InteractsWithInfolists;

    protected static string $resource = BotResource::class;

    protected string $view = 'filament.resources.bot-resource.pages.sending-admin';

    public int $id;

    public int $bot_id;
    public int $bot_message_id;
    public string $bot_name;

    public int $new_bot_id;
    
    protected static ?string $model = Sending::class;

    public static ?string $label = "Рассылка";
    public static ?string $navigationLabel = "Рассылка";
    public static ?string $title = "Рассылка";
    public ?array $data = [];
    public ?array $data_bot_message_link_listener = [];


    public function getRecord(): ?Model
    {
        return Sending::class;
    }

    public function getHeading(): string
    {
        if ($this->id > 0) {
            return "Редактировать рассылку";
        } else {
            return "Добавить рассылку";
        }
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
            $data = ($id>0?Sending::with('bot_message')->find($id)->toArray():["bot_id" => $bot_id]);
        } else {
            $data = [];
            $data['bot_id'] = $bot_id;
        }

        $bot = Bot::select('name')->find($bot_id);
        $this->bot_name = $bot->name;

        $this->form->fill($data);
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
                Section::make('Рассылка')
                    ->description('')
                    ->columns([
                        'sm' => 3,
                        'md' => 3,
                        'lg' => 3,
                        'xl' => 3,
                        '2xl' => 3,
                    ])
                    ->schema([
                        Hidden::make('id'),
                        TextInput::make('name')
                            ->label('Название рассылки')
                            ->required()
                            ->maxLength(255),
                        Select::make('bot_message_id')
                            ->label('Сообщение для бота')
                            ->options(BotMessage::query()->pluck('name', 'id'))
                            ->required()
                            ->validationMessages([
                                'required' => 'Обязательно выберите сообщение',
                            ])
                            ->searchable(),
                        DatePicker::make('send_datetime')
                            ->label('Дата отправления рассылки')
                            ->required()
                            ->validationMessages([
                                'required' => 'Обязательно укажите дату',
                            ])
                    ]),

                Actions::make([
                    Action::make('Сохранить')
                        ->action(function () {
                            $data = $this->form->getState();
                           // $this->bot_id = $data['bot_id'];
                           // $this->bot_message_id = $data['bot_message_id'];

                            if ($this->id>0) {
                                Sending::where('id', $this->id)->update($data);
                            } else {
                                $new_sending = Sending::create($data);
                            }

                            Notification::make()
                                ->title('Данные успешно сохранены!')
                                ->success()
                                ->send();

                            if ($this->id > 0) {
                                return redirect('/admin/bots/' . $this->bot_id . '/sendings');
                            } else {
                                return redirect('/admin/bots/' . $this->bot_id . '/' . $new_sending->id . '/sending-admin');
                            }

                        }),
                    Action::make('Cancel')
                        ->action(function () {
                            return redirect('/admin/bots/' . $this->bot_id . '/sendings');
                        })
                        ->label('Вернуться назад')
                ])
            ])->statePath('data');
    }

  
}