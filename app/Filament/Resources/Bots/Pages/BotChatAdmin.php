<?php
namespace App\Filament\Resources\Bots\Pages;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Checkbox;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use App\Filament\Resources\Bots\BotResource;
use App\Models\Core\Bot;
use App\Models\Core\BotUser;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;

class BotChatAdmin extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = BotResource::class;

    protected string $view = 'filament.resources.bot-resource.pages.bot-telegram-chat';


    public static ?string $label = "Телеграмм чат";
    public static ?string $navigationLabel = "Телеграмм чат";
    public static ?string $title = "Телеграмм чат";

    public ?array $data = [];

    public int $id;

    public int $bot_id;
    public string $bot_name;

    public function getRecord(): ?Model
    {
        return BotUser::class;
    }

    public function getHeading(): string
    {
        return $this->bot_name;
    }

    public function getTitle(): string
    {
        return $this->bot_name;
    }

    public function mount(int $bot_id, int $id): void
    {
        $bot = Bot::select('name')->find($bot_id);
        $this->bot_name = $bot->name;

        $this->id = $id;

        $data = ($id>0?BotUser::find($id)->toArray():[]);

        $this->form->fill($data);
    }

    protected function getForms(): array
    {
        return ['form'];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Email')
                    ->description('Введите Email, который будет привязан к аккаунту пользователя в боте (для сверки доступа)')
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                        '2xl' => 1,
                    ])
                    ->schema([
                        TextInput::make('email')
                            ->label('Email')
                    ]),
                Section::make('Привязка аккаунта к бизнес-боту')
                    ->description('Поставьте галочку, если к данному аккаунту привязан бизнес-бот')
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                        '2xl' => 1,
                    ])
                    ->schema([
                        Checkbox::make('business_bot_account')
                            ->label('Аккаунт привязан к бизнес-боту')

                    ]),
                Actions::make([
                    Action::make('Сохранить')
                        ->action(function () {
                            $data = $this->form->getState();

                            if ($this->id>0) {
                                BotUser::where('id', $this->id)->update($data);
                                $output_id = $this->id;
                            } else {
                                $new = BotUser::create($data);
                                $output_id = $new->id;
                            }

                            Notification::make()
                                ->title('Данные успешно сохранены!')
                                ->success()
                                ->send();

                            return redirect('/admin/bots/'.$this->bot_id.'/chats');
                        }),
                ])
            ])->statePath('data');
    }

}

