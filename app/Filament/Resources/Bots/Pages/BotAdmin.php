<?php
namespace App\Filament\Resources\Bots\Pages;

use App\Actions\Core\Telegram\TelegramDeleteWebhook;
use App\Models\Core\BotBranch;
use App\Models\Core\User;

use Illuminate\Support\HtmlString;

use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Actions;
use Filament\Actions\ViewAction;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Components\Utilities\Set;

use App\Filament\Resources\Bots\BotResource;

use App\Actions\Core\Telegram\TelegramWebhookMake;
use App\Actions\Core\Telegram\TelegramWebhookInfo;
use App\Actions\Core\Telegram\TelegramSetWebhook;

use App\Models\Core\Bot;
use Illuminate\Support\Facades\Auth;
class BotAdmin extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = BotResource::class;

    protected string $view = 'filament.resources.bot-resource.pages.bot-admin';

    protected static ?string $model = Bot::class;

    public static ?string $navigationLabel = "";
    public static ?string $title = "Настройки бота";
    public ?array $data = [];


    public $record;

    public int $id;

    public string $name;

    public string $webhook;

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

    protected function getForms(): array
    {
        return ['form'];
    }

    public function mount(int $id): void
    {
        $this->id = $id;
        $data = ($id>0?Bot::find($id)->toArray():[]);
        $this->form->fill($data);

        if (!Auth::user()->hasPermissionTo('View:Bot')) {
            redirect('/admin/bots/access');
        }
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
                Section::make('Основные параметры')
                    ->description('Укажите основные настройки и заполните все поля')
                    ->columns([
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ])
                    ->schema([
                        TextInput::make('name')
                            ->label('Название (Только в панели администратора)')
                            ->required()
                            ->disabled(auth()->user()->hasPermissionTo('Update:Bot')?false:true)
                            ->maxLength(255),
                        TextInput::make('alias')
                            ->label('Username в Telegram')
                            ->required()
                            ->disabled(auth()->user()->hasPermissionTo('Update:Bot')?false:true)
                            ->maxLength(255),
                        TextInput::make('telegram_token')
                            ->label('Telegram-токен (из BotFather)')
                            ->required()
                            ->disabled(auth()->user()->hasPermissionTo('Update:Bot')?false:true)
                            ->maxLength(255),
                        TextInput::make('telegram_webhook')
                            ->label('Адрес вебхука Telegram')
                            ->required()
                            ->disabled(auth()->user()->hasPermissionTo('Update:Bot')?false:true)
                            ->maxLength(255),

                    ]),
                Actions::make([
                    Action::make('Сохранить')
                        ->action(function () {
                            $data = $this->form->getState();

                            if ($this->id > 0) {
                                Bot::where('id', $this->id)->update($data);
                                return redirect('/admin/bots');
                            } else {
                                $new = Bot::create($data);

                                BotBranch::create(
                                    [
                                        'bot_id' => $new->id,
                                        'bot_branch_type' => 1,
                                        'name' => 'Главная ветка',
                                        'alias' => 'BRANCH_MAIN',
                                        'hash' => 'BRANCH_MAIN',
                                        'datetime_start' => date('Y-m-d H:i:s', time()),
                                        'datetime_end' => date('2100-01-01 00:00:00'),
                                    ]
                                );

                                return redirect('/admin/bots/'.$new->id.'/edit');
                            }

                            Notification::make()
                                ->title('Данные успешно сохранены!')
                                ->success()
                                ->send();
                        })
                        ->visible(auth()->user()->hasPermissionTo('Update:Bot')),
                    Action::make('Cancel')
                         ->action(function () {
                             return redirect('/admin/bots');
                         })
                         ->label('Отменить и вернуться назад')


                ])
            ])->statePath('data');



    }
}
?>
