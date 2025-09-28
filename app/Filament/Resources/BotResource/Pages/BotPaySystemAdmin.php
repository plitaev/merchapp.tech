<?php
namespace App\Filament\Resources\BotResource\Pages;

use App\Filament\Resources\BotResource;
use App\Filament\Resources\BotResource\RelationManagers;
use App\Models\Core\Bot;
use App\Models\Core\TelegramSupergroupLinkBot\TelegramSupergroupLinkBot;
use App\Models\Core\YookassaPaymentMode;
use App\Models\Core\YookassaPaymentSubject;
use App\Models\Core\YookassaTaxSystemCode;
use App\Models\Core\YookassaVatCode;
use Filament\Forms;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;


class BotPaySystemAdmin extends Page implements HasForms
{
    use InteractsWithForms;


    protected static string $resource = BotResource::class;

    protected static string $view = 'filament.resources.bot-resource.pages.bot-pay-system-admin';

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

    public function form(Form $form): Form
    {
        return $form
            ->schema([
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
                        Forms\Components\TextInput::make('yookassa_shop_id')
                            ->label('ID магазина (ShopID)')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('yookassa_shop_secret')
                            ->label('Секретный ключ API (ShopSecret)')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('yookassa_currency')
                            ->label('Валюта (Currency)')
                            ->maxLength(3),
                        Forms\Components\Select::make('yookassa_tax_system_code_id')
                            ->label('Коды систем налогообложения')
                            ->options(YookassaTaxSystemCode::all()->pluck('name', 'id'))
                            ->searchable(),
                        Forms\Components\Select::make('yookassa_vat_code_id')
                            ->label('Признак способа расчета')
                            ->options(YookassaVatCode::all()->pluck('name', 'id'))
                            ->searchable(),
                        Forms\Components\Select::make('yookassa_payment_mode_id')
                            ->label('Коды ставок НДС')
                            ->options(YookassaPaymentMode::all()->pluck('name', 'id'))
                            ->searchable(),
                        Forms\Components\Select::make('yookassa_payment_subject_id')
                            ->label('Признак предмета расчета')
                            ->options(YookassaPaymentSubject::all()->pluck('name', 'id'))
                            ->searchable()

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
                        }),
                ])
            ])->statePath('data');
    }

}
