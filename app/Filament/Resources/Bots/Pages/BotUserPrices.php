<?php
namespace App\Filament\Resources\Bots\Pages;

use App\Models\Core\BotUserBanSchedule;
use App\Models\Core\TelegramSendMessageLog;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use App\Models\Core\TelegramSendMessageSchedule;
use Illuminate\Support\HtmlString;


use App\Filament\Resources\Bots\BotResource;

use Filament\Forms;

use Filament\Infolists\Contracts\HasInfolists;

use Filament\Forms\Components;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Concerns\InteractsWithForm;
use Filament\Forms\Contracts\HasForms;

use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Forms\Form;


use App\Models\Core\Bot;
use App\Models\Core\BotUserPrice;
use App\Models\Core\Product;
use App\Models\Core\BotUser;
use Illuminate\Support\Facades\Auth;

class BotUserPrices extends Page implements HasTable, HasForms, HasInfolists
{
    use InteractsWithForms;
  
    use InteractsWithTable;

    use InteractsWithInfolists;


    protected static string $resource = BotResource::class;

    protected string $view = 'filament.resources.bot-resource.pages.bot-user-prices';

    public int $id;

    public int $bot_id;
    public int $bot_message_id;

    public int $b_user_id;
    public int $bot_user_id;
    public int $bot_user;

    public string $bot_name;


    protected static ?string $model = BotUserPrice::class;

    public static ?string $label = "Индивидуальная цена";
    public static ?string $navigationLabel = "Индивидуальная цена";
    public static ?string $title = "Индивидуальная цена";
    public ?array $data = [];

//    public function getRecord(): ?Model
//    {
//        return BotUserPrice::class;
//    }

    public function getHeading(): string
    {

        return $this->bot_name;

    }

    protected function getHeaderActions(): array
    {
        return [];
    }


    public function mount(int $bot_id, int $bot_user_id, int $id): void
    {

        $this->id = $id;
        $bot = Bot::select('name')->find($bot_id);
        $this->bot_name = $bot->name;

        $this->bot_id = $bot_id;
        $this->bot_user_id = $bot_user_id;

        if($id > 0) {
            $botUserPrices = BotUserPrice::find($this->id);
        }else {
            $botUserPrices = BotUserPrice::get();
        }
//        if (!Auth::user()->hasPermissionTo('View:BotUserBanSchedule')) {
//            redirect('/admin/bots/access');
//        }
    }

    public function getTitle(): string
    {
        return "Индивидуальная  цена";
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('updated_at', 'desc')
            ->query(
                BotUserPrice::with('products')->where('bot_user_id', $this->bot_user_id)
            )
            ->columns([
                TextColumn::make('products.name')
                    ->label('Продукт'),
                TextColumn::make('price')
                    ->label('Цена'),
            ])
            ->recordActions([
                EditAction::make()->url(fn($record) => "/admin/bots/".$this->bot_id."/".$this->bot_user_id."/".$record->id."/users-prices"),
                    //->visible(auth()->user()->can('Update:TelegramSupergroup')),
                DeleteAction::make()
                    //->visible(auth()->user()->can('Delete:TelegramSupergroup')),

            ])

            ->filters([
                //
            ]);
    }

    protected function getForms(): array
    {
        return ['form'];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Индивидуальная  цена')
                    ->description('Укажите цены, чтобы продолжить работу')
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                        '2xl' => 1,
                    ])
                    ->schema([
                        Hidden::make('id'),
                        Hidden::make('bot_user_id'),
                        Select::make('product_id')
                            ->label('Продукт')
                            ->options(Product::query()->pluck('name','id'))
                            ->searchable()
                            ->live(),
                           // ->disabled(fn() => ['readonly' => auth()->user()->can('Update:BotUserUnBanSchedule')?true:false]),

                        TextInput::make('price')
                            ->label('Цена')
                            ->required()
                            ->maxLength(255)
                        //
                    ]),

                Actions::make([
                    Action::make('Сохранить')
                        ->action(function () {
                            $data = $this->form->getState();

                            $data['bot_user_id'] = $this->bot_user_id;

                            if ($this->id > 0) {
                                BotUserPrice::where('id', $this->id)->update($data);
                            }else{
                                $new_bot_user_prices= BotUserPrice::create($data);
                            }

                            Notification::make()
                                ->title('Данные успешно сохранены!')
                                ->success()
                                ->send();

                            if ($this->id>0) {
                                return redirect('/admin/bots/' . $this->bot_user_id .'/'.$this->id. '/bot-users-prices');
                            }else{
                                return redirect('/admin/bots/' . $this->bot_user_id . '/bot-chats');
                            }

                        }),
                     //   ->disabled(fn() => ['readonly' => auth()->user()->can('Create:BotUserUnBanSchedule')?true:false]),
                    Action::make('Cancel')
                        ->action(function () {
                            return redirect('/admin/bots/' . $this->bot_user_id . '/chat-admin');
                        })
                        ->label('Отменить и вернуться назад')
                ]),

            ])->statePath('data');

    }


}
