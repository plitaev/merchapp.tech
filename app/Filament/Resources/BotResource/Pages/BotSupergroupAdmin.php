<?php
namespace App\Filament\Resources\BotResource\Pages;

use App\Filament\Resources\BotResource;

use Filament\Forms;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;

use App\Models\Core\Bot;
use App\Models\Core\SupergroupDeleteParameter;
use App\Models\Core\TelegramSupergroup;

class BotSupergroupAdmin extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = BotResource::class;

    protected static string $view = 'filament.resources.bot-resource.pages.bot-supergroup-admin';

    protected static ?string $model = TelegramSupergroup::class;

    public static ?string $label = "Супергруппа";
    public static ?string $navigationLabel = "Супергруппа";
    public static ?string $title = "Супергруппа";

    public ?array $data = [];

    public int $bot_id;
    public string $bot_name;

    public int $id;

    public string $name;

    public function getRecord(): ?Model
    {
        return TelegramSupergroup::class;
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
            $data = TelegramSupergroup::find($id)->toArray();
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
            return "Редактировать супергруппу";
        } else {
            return "Добавить супергруппу";
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

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Параметры супергруппы')
                    ->description('Укажите название супергруппы и её ID в Telegram. ID в Telegram - это число, начинающееся с -100 (например, -10012345678998765)')
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ])
                    ->schema([
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
                    ]),
                Section::make('Выдача доступа')
                    ->description('Если включено, бот будет выдавать доступ пользователю в эту группу при успешной проверке доступа')
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                        '2xl' => 1,
                    ])
                    ->schema([
                        Forms\Components\Checkbox::make('give_access')
                            ->label('Выдавать доступ в эту группу')
                    ]),
                Section::make('Удаление участников')
                    ->description('Укажите, когда бот должен удалять участников из этой супергруппы')
                    ->columns([
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ])
                    ->schema([
                        Forms\Components\Select::make('supergroup_delete_parameter_id')
                            ->label('Режим удаления')
                            ->required()
                            ->options(SupergroupDeleteParameter::all()->pluck('name', 'id'))
                            ->live()
                            ->searchable(),
                        Forms\Components\TextInput::make('supergroup_delete_hours')
                            ->label(function (Forms\Get $get) {
                                if (is_callable($get)) {
                                    if ($get('funnel_condition_trigger_id') == 2) {
                                        return 'За сколько дней до окончания подписки';
                                    }

                                    if ($get('funnel_condition_trigger_id') == 3) {
                                        return 'Через сколько дней после окончания подписки';
                                    }
                                }
                            })
                            ->maxLength(255)
                            ->visible(function (Forms\Get $get) {
                                if (is_callable($get)) {
                                    return $get('funnel_condition_trigger_id') > 1;
                                }
                            }),
                    ]),
                Actions::make([
                    Action::make('Сохранить')
                        ->action(function () {
                            $data = $this->form->getState();

                            if ($this->id>0) {
                                TelegramSupergroup::where('id', $this->id)->update($data);
                                $output_id = $this->id;
                            } else {
                                $new = TelegramSupergroup::create($data);
                                $output_id = $new->id;
                            }

                            Notification::make()
                                ->title('Данные успешно сохранены!')
                                ->success()
                                ->send();

                            return redirect('/admin/bots/' . $this->bot_id . '/supergroups');
                        }),
                ])
            ])->statePath('data');
    }

}
