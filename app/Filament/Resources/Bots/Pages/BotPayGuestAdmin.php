<?php
namespace App\Filament\Resources\Bots\Pages;

use App\Models\Core\BotUser;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Checkbox;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use App\Filament\Resources\Bots\BotResource;
use App\Models\Core\Bot;
use App\Models\Core\Pay;
use App\Models\Core\PayGuest;
use App\Models\Core\PayLinkBot\PayLinkBot;
use App\Models\Core\Product;
use App\Models\Core\User;

use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;

use App\Actions\Core\Pay\PayCreateByPayGuest;

class BotPayGuestAdmin extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = BotResource::class;

    protected string $view = 'filament.resources.bot-resource.pages.bot-pay-guest-admin';

    protected static ?string $model = Pay::class;

    public static ?string $label = "Платеж в ожидании";
    public static ?string $navigationLabel = "Платеж в ожидании";
    public static ?string $title = "Платеж в ожидании";

    public ?array $data = [];

    public int $bot_id;
    public string $bot_name;

    public int $id;

    public string $name;

    public function getRecord(): ?Model
    {
        return Pay::class;
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
            $A = PayGuest::with('product')->find($id)->toArray();
            $A['bot_id'] = $A['product']['bot_id'];
            $data = $A;
        } else {
            $data = [];
            $data['bot_id'] = $bot_id;
        }

        $bot = Bot::select('name')->find($bot_id);
        $this->bot_name = $bot->name;

        $this->form->fill($data);
    }

    public function getHeading(): string
    {
        if ($this->id > 0) {
            return "Редактировать платеж";
        } else {
            return "Добавить платеж";
        }
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
                Section::make('Параметры платежа')
                    ->description('Укажите продукт, цену, продолжительность и email.')
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ])
                    ->schema([
//                        Forms\Components\Hidden::make('bot_id'),
                        Select::make('product_id')
                            ->label('Продукт')
                            ->required()
                            ->options(function (Get $get) {
                                if (is_callable($get)) {
                                    return Product::query()
                                        ->where('bot_id', $this->bot_id)
                                        ->pluck('name', 'id');
                                }
                            })
                            ->live()
                            ->afterStateUpdated(function (Set $set, ?string $state, ?string $old) {
                                $product = Product::find($state);

                                if (is_callable($set)) {
                                    if (isset($product->price)) $set('price', $product->price);
                                    if (isset($product->days)) $set('days', $product->days);
                                }

                            }),
                        ]),
                        Section::make('Цена и почта')
                            ->description('Проверьте, и в случае необходимости измените стоимость в рублях и количество дней')
                            ->columns([
                                'sm' => 2,
                                'md' => 2,
                                'lg' => 2,
                                'xl' => 2,
                                '2xl' => 2
                            ])
                            ->schema([
                                TextInput::make('price')
                                    ->label('Стоимость')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('days')
                                    ->label('Дни')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('email')
                                    ->label('Email')
                                    ->required()
                                    ->maxLength(255),
                                Checkbox::make('gift')
                                    ->label('Подарок')
                            ]),

                        Actions::make([
                            Action::make('Сохранить')
                                ->action(function () {
                                    $data = $this->form->getState();

                                    PayGuest::where('id', $this->id)->update($data);

                                    $payCreateByPayGuest = new PayCreateByPayGuest();
                                    $bot_user = BotUser::where('email', $data['email'])->first();
                                    if ($bot_user) $payCreateByPayGuest->handle($bot_user, $data['email']);


                                    Notification::make()
                                        ->title('Данные успешно сохранены!')
                                        ->success()
                                        ->send();

                                    if ($this->id>0) {
                                        return redirect('/admin/bots/' . $this->bot_id . '/pay-guests');
                                    } else {
                                        return redirect('/admin/bots/'.$this->bot_id.'/'.$this->id.'/pay-guest-admin');
                                    }
                                })
                                ->visible(fn() => auth()->user()->can('Create:PayGuests')),

                            Action::make('Cancel')
                                ->action(function () {
                                    return redirect('/admin/bots/'.$this->bot_id.'/pay-guests');
                                })
                                ->label('Отменить и вернуться назад')
                        ]),
            ])->statePath('data');
    }

}
