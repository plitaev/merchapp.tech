<?php

namespace App\Filament\Resources\Bots\Pages;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use App\Actions\Core\BotMessageButton\BotMessageButtonBuildPosList;
use App\Filament\Resources\Bots\BotResource;
use App\Models\Core\Bot;
use App\Models\Core\BotMessage;
use App\Models\Core\BotMessageButton;
use App\Models\Core\BotMessageButtonCallback;
use App\Models\Core\BotMessageButtonType;
use App\Models\Core\PaySystem;
use App\Models\Core\Product;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;
class BotMessageButtonAdmin extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = BotResource::class;

    protected string $view = 'filament.resources.bot-resource.pages.bot-message-button-admin';

    protected static ?string $model = BotMessageButton::class;

    public static ?string $label = "Кнопка";
    public static ?string $navigationLabel = "Кнопка";
    public static ?string $title = "Кнопка";

    public $pos_list;

    public ?array $data = [];

    public int $bot_message_id;
    public int $id;

    public int $bot_id;
    public string $bot_name;

    public function getRecord(): ?Model
    {
        return BotMessageButton::class;
    }

    public function mount(int $bot_message_id, int $id): void
    {
        $this->id = $id;
        $this->bot_message_id = $bot_message_id;
        $bot_message = BotMessage::select('bot_id')->find($bot_message_id);

        $bot = Bot::select('name')->find($bot_message->bot_id);
        $this->bot_id = $bot_message->bot_id;
        $this->bot_name = $bot->name;

        $this->pos_list = (new BotMessageButtonBuildPosList())->handle($bot_message_id, $id);
        $data = ($id>0?BotMessageButton::find($id)->toArray():["bot_message_id" => $bot_message_id, 'pos' => $this->pos_list[1]]);
        $this->form->fill($data);

        if (!Auth::user()->hasPermissionTo('View:BotMessageButton')) {
            redirect('/access');
        }
    }

    public function getHeading(): string
    {
        if ($this->id > 0) {
            return "Редактировать кнопку";
        } else {
            return "Добавить кнопку";
        }
    }

    public function getTitle(): string
    {
        return $this->bot_name;
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getForms(): array
    {
        return ['form'];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Тип кнопки и надпись, которую видит пользователь')
                    ->description('Укажите основные настройки, чтобы продолжить работу')
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ])
                    ->schema([
                        Hidden::make('bot_message_id'),
                        Select::make('bot_message_button_type_id')
                            ->label('Тип кнопки')
                            ->required()
                            ->options(BotMessageButtonType::all()->pluck('name', 'id'))
                            ->searchable()
                            ->live()
                            ->disabled(auth()->user()->hasPermissionTo('Update:BotMessageButton')?false:true),

                        TextInput::make('name')
                            ->required()
                            ->label('Надпись на кнопке')
                            ->maxLength(255)
                            ->disabled(auth()->user()->hasPermissionTo('Update:BotMessageButton')?false:true),

                        Select::make('pos')
                            ->label('Порядковый номер')
                            ->required()
                            ->options($this->pos_list[0])
                            ->searchable()
                            ->columns(['sm' => 2, 'xl' => 2, '2xl' => 2])
                            ->disabled(auth()->user()->hasPermissionTo('Update:BotMessageButton')?false:true),

                    ]),
                Section::make('Сбор статистики')
                    ->description('Если включено, вы сможете просматривать статистику нажатия кнопки пользователями')
                    ->schema([
                        Toggle::make('tracking')
                            ->label('Отслеживать статистику')
                            ->disabled(auth()->user()->hasPermissionTo('Update:BotMessageButton')?false:true),

                    ]),
                Section::make('Ссылка на кнопке')
                    ->description('Веб-страница, которая будет открываться пользователю по клику на кнопку')
                    ->schema([
                        TextInput::make('url')
                            ->label('Укажите полный адрес ссылки вместе с https://')
                            ->maxLength(255)
                            ->disabled(auth()->user()->hasPermissionTo('Update:BotMessageButton')?false:true),

                    ])
                    ->visible(function (Get $get) {
                        if (is_callable($get)) {
                            return $get('bot_message_button_type_id') == 1;
                        }
                    })
                    ->disabled(auth()->user()->hasPermissionTo('Update:BotMessageButton')?false:true),

                Section::make('Выберите функцию, которая должна вызываться по клику на кнопке')
                    ->schema([
                        Select::make('bot_message_callback_id')
                            ->label('Функция')
                            ->options(BotMessageButtonCallback::all()->pluck('name', 'id'))
                            ->searchable()
                            ->live()
                            ->disabled(auth()->user()->hasPermissionTo('Update:BotMessageButton')?false:true),

                    ])
                    ->visible(function (Get $get) {
                        if (is_callable($get)) {
                            return $get('bot_message_button_type_id') == 2;
                        }
                    }),
                Section::make('Своя функция')
                    ->schema([
                        TextInput::make('callback')
                            ->label('Введите имя функции, которое будет использоваться в скриптах бота')
                            ->maxLength(255)
                            ->disabled(auth()->user()->hasPermissionTo('Update:BotMessageButton')?false:true),

                    ])
                    ->visible(function (Get $get) {
                        if (is_callable($get)) {
                            return $get('bot_message_button_type_id') == 3;
                        }
                    }),
                Section::make('Тариф')
                    ->schema([
                        Select::make('pay_system_id')
                            ->label('Платёжная система')
                            ->options(PaySystem::all()->pluck('name', 'id'))
                            ->searchable()
                            ->disabled(auth()->user()->hasPermissionTo('Update:BotMessageButton')?false:true),

                        Select::make('product_id')
                            ->label('Продукт')
                            ->options(Product::all()->pluck('name', 'id'))
                            ->searchable()
                            ->disabled(auth()->user()->hasPermissionTo('Update:BotMessageButton')?false:true),

                    ])
                    ->visible(function (Get $get) {
                        if (is_callable($get)) {
                            return $get('bot_message_button_type_id') == 4;
                        }
                    }),
                Actions::make([
                    Action::make('Сохранить')
                        ->action(function () {
                            $data = $this->form->getState();

                            if ($this->id>0) {
                                BotMessageButton::where('id', $this->id)->update($data);
                                $button_id = $this->id;
                            } else {
                                $new_button = BotMessageButton::create($data);
                                $button_id = $new_button->id;
                            }

                            //=========================================================================

                            $posres = BotMessageButton::select('id')
                                ->where('bot_message_id', $this->bot_message_id)
                                ->whereNot('id', $button_id)
                                ->where('pos', '<=', $data['pos'])
                                ->orderBy('pos')
                                ->get();

                            $count = 0;
                            foreach ($posres as $posdata) {
                                $count = $count + 1;
                                BotMessageButton::where('id', $posdata->id)->update(['pos' => $count]);
                            }

                            //===

                            $posres = BotMessageButton::select('id')
                                ->where('bot_message_id', $this->bot_message_id)
                                ->whereNot('id', $button_id)
                                ->where('pos', '>=', $data['pos'])
                                ->orderBy('pos')
                                ->get();

                            $count = $data['pos'];
                            foreach ($posres as $posdata) {
                                $count = $count + 1;
                                BotMessageButton::where('id', $posdata->id)->update(['pos' => $count]);
                            }

                            //=========================================================================

                            Notification::make()
                                ->title('Данные успешно сохранены!')
                                ->success()
                                ->send();

                            return redirect('/admin/bots/'.$this->bot_id.'/'.$this->bot_message_id.'/message-admin');
                        })
                        ->visible(auth()->user()->hasPermissionTo('Create:BotMessageButton')),



                    Action::make('Cancel')
                        ->action(function () {
                            return redirect('/admin/bots/'.$this->bot_id.'/'.$this->bot_message_id.'/message-admin');
                        })
                    ->label('Отменить и вернуться назад')
                ])
            ])->statePath('data');
    }

}
