<?php
namespace App\Filament\Resources\Bots\Pages;
use Illuminate\Support\HtmlString;

use App\Models\Core\ProdamusTax;
use App\Models\Core\RobokassaPaymentMethod;
use App\Models\Core\RobokassaPaymentObject;
use App\Models\Core\RobokassaVAT;
use App\Models\Core\TbankTax;
use App\Models\Core\TbankTaxation;
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
use App\Models\Core\RobokassaTax;
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
use Illuminate\Support\Facades\Auth;

class BotPaySystemTestAdmin extends Page implements HasForms
{
    use InteractsWithForms;


    protected static string $resource = BotResource::class;

    protected string $view = 'filament.resources.bot-resource.pages.bot-pay-system-test-admin';

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

    public function mount(): void
    {

        if (!Auth::user()->hasPermissionTo('View:PaySystem')) {
            redirect('/admin/bots/access');
        }

        $data = [];
        $this->form->fill($data);

    }

    public function getHeading(): string
    {
        return '';
    }

    public function getTitle(): string
    {
        return '';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('ТБанк')
                    ->description('Тестирование платежной системы ТБанк')
                    ->columns([
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ])
                    ->schema([

                        TextInput::make('terminalkey')
                            ->label('Ключ терминала')
                            ->maxLength(255),
                        TextInput::make('language')
                            ->label('Язык')
                            ->maxLength(255),
                        TextInput::make('amount')
                            ->label('Сумма')
                            ->maxLength(255),
                        TextInput::make('order')
                            ->label('ID заказа на стороне merchApp')
                            ->maxLength(255),
                        TextInput::make('description')
                            ->label('Описание')
                            ->maxLength(255),
                        TextInput::make('name')
                            ->label('Фамилия и имя клиента')
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Email клиента')
                            ->maxLength(255),
                        TextInput::make('receipt')
                            ->label('JSON чека (Проверьте совпадение цены, почты и ФИО клиента)')
                            ->maxLength(255),
                        Toggle::make('frame')
                            ->label('Открывать во всплывающем окне'),
                    ]),
                Actions::make([
                    Action::make('Оплатить')
                        ->action(function () {
                        })
                        ->visible(auth()->user()->hasPermissionTo('Create:Bot'))
                ])
            ])->statePath('data');
    }

}
