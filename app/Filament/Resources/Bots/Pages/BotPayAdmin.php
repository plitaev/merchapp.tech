<?php
namespace App\Filament\Resources\Bots\Pages;

use App\Actions\Core\BotSendMessage\BotSendMessage;
use App\Actions\Core\BotUser\BotUserBanByDeletePay;
use App\Models\Core\BotAdminLog;
use App\Models\Core\BotUser;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use App\Actions\Core\DateEnd\DateEnd;
use App\Actions\Core\DateEnd\DateEndCacheForPay;
use App\Actions\Core\Pay\PayCreateByEmail;
use App\Actions\Core\Pay\PayRefund;
use App\Filament\Resources\Bots\BotResource;
use App\Models\Core\Bot;
use App\Models\Core\Pay;
use App\Models\Core\PayLinkBot\PayLinkBot;
use App\Models\Core\Product;
use App\Models\Core\User;

use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;

class BotPayAdmin extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = BotResource::class;

    protected string $view = 'filament.resources.bot-resource.pages.bot-pay-admin';

    protected static ?string $model = Pay::class;

    public static ?string $label = "Платеж";
    public static ?string $navigationLabel = "Платеж";
    public static ?string $title = "Платеж";

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
            $data = Pay::find($id)->toArray();
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
                        Hidden::make('bot_id'),
                        Select::make('product_id')
                            ->label('Продукт')
                            ->required()
                            ->options(
                                Product::query()
                                    ->where('bot_id', $this->bot_id)
                                    ->pluck('name', 'id')
                            )
                            ->live()
                            ->afterStateUpdated(function (Set $set, ?string $state, ?string $old) {
                                $product = Product::find($state);

                                if (is_callable($set)) {
                                    if (isset($product->price)) $set('price', $product->price);
                                    if (isset($product->days)) $set('days', $product->days);
                                }

                            }),
                ]),
                Section::make('Цена и продолжительность')
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
                            ->required()
                            ->validationMessages(['required' => 'Обязательно укажите стоимость'])
                            ->label('Стоимость')
                            ->maxLength(50),
                        TextInput::make('days')
                            ->required()
                            ->validationMessages(['required' => 'Обязательно укажите дни'])
                            ->label('Дни')
                            ->maxLength(50),
                    ]),
                Section::make('Email')
                    ->description('Введите почту пользователя, который совершил платёж')
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                        '2xl' => 1
                    ])
                    ->schema([
                        TextInput::make('email')
                            ->required()
                            ->validationMessages(['required' => 'Обязательно укажите email'])
                            ->label('Email')
                            ->maxLength(255)
                    ])
                    ->visible($this->id > 0?0:1),
                Actions::make([
                    Action::make('Сохранить')
                        ->action(function () {
                            $dateEndCacheForPay = new DateEndCacheForPay();
                            $payCreateByEmail = new PayCreateByEmail();
                            $data = $this->form->getState();

                            if ($this->id>0) {

                                unset($data['email']);
                                unset($data['bot_id']);

                                Pay::where('id', $this->id)->update($data);

                                $pay_all_data = Pay::with('bot')->find($this->id);

                                Notification::make()
                                    ->title($pay_all_data)
                                    ->success()
                                    ->send();

                                $bot_user_id = ($pay_all_data->gift_bot_user_id ?? $pay_all_data->bot_user_id);
                                $dateEndCacheForPay->handle($bot_user_id);

                                $output_id = $this->id;
                            } else {
                                $payCreateByEmail->handle($data['email'], $data['product_id'], 0, 0, $data['days'], $data['price']);
                                $output_id = $this->id;
                            }


                            Notification::make()
                                ->title('Данные успешно сохранены!')
                                ->success()
                                ->send();

                            return redirect('/admin/bots/'.$this->bot_id.'/pays');
                        })
                        ->disabled(fn() => auth()->user()->can('Create:Pay')),

                    Action::make('Вернуть платеж')
                        ->color('info')
                        ->requiresConfirmation()
                        ->action(function () {
                            $payRefund = new PayRefund();
                            $payRefund->handle($this->id);
                            return redirect('/admin/bots/'.$this->bot_id.'/pays');
                        })
                    ->label('Вернуть платёж')
                    ->visible($this->id > 0)
                        ->disabled(fn() => auth()->user()->can('Update:Pay')),


                    Action::make('Cancel')
                        ->color('gray')
                        ->action(function () {
                            return redirect('/admin/bots/'.$this->bot_id.'/pays');
                        })
                        ->label('Отменить и вернуться назад')
                ]),
            ])->statePath('data');
    }

}
