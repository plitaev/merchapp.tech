<?php
namespace App\Filament\Resources\Bots\Pages;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use App\Filament\Resources\Bots\BotResource;
use App\Filament\Resources\BotResource\RelationManagers;
use App\Models\Core\Bot;
use App\Models\Core\ProdamusNpdIncomeType;
use App\Models\Core\ProdamusPaymentMethod;
use App\Models\Core\ProdamusPaymentObject;
use App\Models\Core\TelegramSupergroupLinkBot\TelegramSupergroupLinkBot;
use App\Models\Core\YookassaPaymentMode;
use App\Models\Core\YookassaPaymentSubject;
use App\Models\Core\YookassaTaxSystemCode;
use App\Models\Core\YookassaVatCode;
use App\Models\Core\User;

use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\Toggle;


class BotPaySystemAdmin extends Page implements HasForms
{
    use InteractsWithForms;


    protected static string $resource = BotResource::class;

    protected string $view = 'filament.resources.bot-resource.pages.bot-pay-system-admin';

    protected static ?string $model = Bot::class;

    public static ?string $navigationLabel = "";
    public static ?string $title = "Настройки бота";
    public ?array $data = [];

    public $record;

    public int $id;

    public string $name;

    public function getRecord(): ?Model
    {
        return Bot::class;
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getForms(): array
    {
        return ['form'];
    }

    public function mount(int $record): void
    {
        $this->id = $record;

        if ($record > 0) {
            $bot = Bot::find($record)->toArray();
            $this->name = $bot['name'];
        } else {
            $bot = [];
            $this->name = 'Новая платежная система';
        }

        $data = ($record>0?Bot::find($record)->toArray():[]);
        $this->form->fill($data);
    }

    public function getHeading(): string
    {
        return $this->name;
    }

    public function getTitle(): string
    {
        return $this->name;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Юкасса')
                    ->description('Настройки платежной системы Юкасса')
                    ->columns([
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ])
                    ->schema([
                        TextInput::make('yookassa_shop_id')
                            ->label('ID магазина (ShopID)')
                            ->maxLength(255)
                            ->disabled(auth()->user()->hasPermissionTo('Update:PaySystem')?false:true),

                        TextInput::make('yookassa_shop_secret')
                            ->label('Секретный ключ API (ShopSecret)')
                            ->maxLength(255)
                            ->disabled(auth()->user()->hasPermissionTo('Update:PaySystem')?false:true),

                        TextInput::make('yookassa_currency')
                            ->label('Валюта (Currency)')
                            ->maxLength(3)
                            ->disabled(auth()->user()->hasPermissionTo('Update:PaySystem')?false:true),

                        Select::make('yookassa_tax_system_code_id')
                            ->label('Коды систем налогообложения')
                            ->options(YookassaTaxSystemCode::query()->pluck('name', 'id'))
                            ->searchable()
                            ->disabled(auth()->user()->hasPermissionTo('Update:PaySystem')?false:true),

                        Select::make('yookassa_vat_code_id')
                            ->label('Признак способа расчета')
                            ->options(YookassaVatCode::query()->pluck('name', 'id'))
                            ->searchable()
                            ->disabled(auth()->user()->hasPermissionTo('Update:PaySystem')?false:true),

                        Select::make('yookassa_payment_mode_id')
                            ->label('Коды ставок НДС')
                            ->options(YookassaPaymentMode::query()->pluck('name', 'id'))
                            ->searchable()
                            ->disabled(auth()->user()->hasPermissionTo('Update:PaySystem')?false:true),

                        Select::make('yookassa_payment_subject_id')
                            ->label('Признак предмета расчета')
                            ->options(YookassaPaymentSubject::query()->pluck('name', 'id'))
                            ->searchable()
                            ->disabled(auth()->user()->hasPermissionTo('Update:PaySystem')?false:true),

                        Toggle::make('yookassa_recurrent')
                            ->label('Рекуррентные платежи подключены')
                            ->disabled(auth()->user()->hasPermissionTo('Update:PaySystem')?false:true),

                    ]),
                Section::make('Продамус')
                    ->description('Настройки платежной системы Продамус')
                    ->columns([
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ])
                    ->schema([
                        Select::make('prodamus_payment_method_id')
                            ->label('Тип оплаты')
                            ->options(ProdamusPaymentMethod::query()->pluck('name', 'id'))
                            ->searchable()
                            ->disabled(auth()->user()->hasPermissionTo('Update:PaySystem')?false:true),

                        Select::make('prodamus_payment_object_id')
                            ->label('Тип оплачиваемой позиции')
                            ->options(ProdamusPaymentObject::query()->pluck('name', 'id'))
                            ->searchable()
                            ->disabled(auth()->user()->hasPermissionTo('Update:PaySystem')?false:true),

                        Select::make('prodamus_npd_income_type_id')
                            ->label('Тип плательщика')
                            ->options(ProdamusNpdIncomeType::query()->pluck('name', 'id'))
                            ->searchable()
                            ->disabled(auth()->user()->hasPermissionTo('Update:PaySystem')?false:true),

                        TextInput::make('prodamus_sys')
                            ->label('Параметр sys')
                            ->maxLength(255)
                            ->disabled(auth()->user()->hasPermissionTo('Update:PaySystem')?false:true),

                        TextInput::make('prodamus_url')
                            ->label('Ссылка на платежную страницу')
                            ->maxLength(255)
                            ->disabled(auth()->user()->hasPermissionTo('Update:PaySystem')?false:true),

                        TextInput::make('prodamus_key')
                            ->label('Ключ')
                            ->maxLength(255)
                            ->disabled(auth()->user()->hasPermissionTo('Update:PaySystem')?false:true),

                        TextInput::make('prodamus_key_recurrent')
                            ->label('Ключ рекуррента')
                            ->maxLength(255)
                            ->disabled(auth()->user()->hasPermissionTo('Update:PaySystem')?false:true),

                    ]),
                Actions::make([
                    Action::make('Сохранить')
                        ->action(function () {
                            $data = $this->form->getState();

                            if ($this->id > 0) {
                                Bot::where('id', $this->id)->update($data);
                                return redirect('/admin/bots/'.$this->id.'/pay-system-admin');
                            } else {
                                $new = Bot::create($data);
                                return redirect('/admin/bots/'.$new->id.'/pay-system-admin');
                            }

                            Notification::make()
                                ->title('Данные успешно сохранены!')
                                ->success()
                                ->send();
                        })
                        ->visible(auth()->user()->hasPermissionTo('Create:Bot'))
               ])
            ])->statePath('data');
    }

}
