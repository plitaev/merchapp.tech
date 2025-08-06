<?php
namespace App\Filament\Resources\BotResource\Pages;

use App\Filament\Resources\BotResource;
use App\Models\Core\Bot;
use App\Models\Core\PayLinkBot\PayLinkBot;
use App\Models\Core\TelegramSupergroup;
use Filament\Forms;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;


class BotWizard extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = BotResource::class;

    protected static string $view = 'filament.resources.bot-resource.pages.bot-wizard';


    public static ?string $label = "BotWizard";
    public static ?string $navigationLabel = "BotWizard";
    public static ?string $title = "BotWizard";
    public ?array $data = [];
    public ?array $data2 = [];

    public $record;

    public int $new_bot;
    public int $bot_id;
    public int $supergroup_id;
    public string $bot_name;

    public int $id;

    public string $name;

    public array $hours = [
        '0' => '0',
        '1' => '1',
        '2' => '2',
        '3' => '3',
        '4' => '4',
        '5' => '5',
        '6' => '6',
        '7' => '7',
        '8' => '8',
        '9' => '9',
        '10' => '10',
        '11' => '11',
        '12' => '12',
        '13' => '13',
        '14' => '14',
        '15' => '15',
        '16' => '16',
        '17' => '17',
        '18' => '18',
        '19' => '19',
        '20' => '20',
        '21' => '21',
        '22' => '22',
        '23' => '23',
        '24' => '24',
        '25' => '25',
        '26' => '26',
        '27' => '27',
        '28' => '28',
        '29' => '29',
        '30' => '30',
        '31' => '31',
        '32' => '32',
        '33' => '33',
        '34' => '34',
        '35' => '35',
        '36' => '36',
        '37' => '37',
        '38' => '38',
        '39' => '39',
        '40' => '40',
        '41' => '41',
        '42' => '42',
        '43' => '43',
        '44' => '44',
        '45' => '45',
        '46' => '46'
    ];

    public array $minutes = [
        '0' => '0',
        '15' => '15',
        '30' => '30',
        '45' => '45'
    ];

    public function getRecord(): ?Model
    {
        return Bot::class;
    }

    protected function getHeaderActions(): array
    {
        return [];
    }


    public function mount(): void
    {
        $bot = [];
        $this->name = 'Новый бот';
      //  $bot_id = $this->id;
        $data = [];
        $this->form->fill($data);
    }

    public function getHeading(): string
    {
        return "Добавить бот";
    }


    protected function getForms(): array
    {
        return ['form'];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Cоздание бота')
                    ->description('Выполнение пошагово')

            ->schema([
                Wizard::make([

                    Wizard\Step::make('Создание бота')

                        ->schema([

                            Actions::make([
                                Action::make('Добавить бота')

                                    ->action(function() {
                                        $data = $this->form->getState();

                                        $new_bot = Bot::create($data);
                                        $this->bot_id = $new_bot->id;

                                        Notification::make()
                                            ->title('Данные успешно сохранены22!')
                                            ->success()
                                            ->send();
                                    })

                            ]),

                        ]),


                    Wizard\Step::make('Настройка веб-хука')
                        ->schema([

                        Actions::make([
                            Action::make('Добавить супергруппу')
                               ->form([
                                       Forms\Components\Hidden::make('bot_id'),
                                       Forms\Components\TextInput::make('name')
                                           ->required()
                                           ->validationMessages([
                                               'required' => 'Обязательно укажите название',
                                           ])
                                           ->label('Название супергруппы (только в панели администратора)')
                                           ->maxLength(255),
                                       Forms\Components\TextInput::make('telegram_id')
                                           ->required()
                                           ->validationMessages([
                                               'required' => 'Обязательно укажите ID в Telegram (только цифры)',
                                           ])
                                           ->label('ID в Telegram')
                                           ->maxLength(255)
                                       ])
                           ->action(function() {
                               $data2 = $this->form->getState();

                                $new = TelegramSupergroup::create($data2);
                                $output_id = $new->id;

                                Notification::make()
                                    ->title('Данные успешно сохранены!')
                                    ->success()
                                    ->send();
                           }),
                        ]),
                        ]),


                    Wizard\Step::make('Создание групп')
                        ->schema([

                        ]),
                    Wizard\Step::make('Создание продуктов')
                        ->schema([
                            //Forms\Components\Hidden::make('bot_id'),


                        ]),
                        ]),
            ]),

            ])->statePath('data');
    }

}
