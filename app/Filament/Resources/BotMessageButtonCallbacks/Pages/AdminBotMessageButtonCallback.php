<?php

namespace App\Filament\Resources\BotMessageButtonCallbacks\Pages;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use App\Filament\Resources\BotMessageButtonCallbacks\BotMessageButtonCallbackResource;
use App\Models\Core\BotMessageButtonCallback;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;


class AdminBotMessageButtonCallback extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = BotMessageButtonCallbackResource::class;

    protected string $view = 'filament.resources.bot-message-button-callback-resource.pages.admin-bot-message-button-callback';

    public static ?string $label = "Обработчик кнопок";
    public static ?string $navigationLabel = "Обработчик кнопок";
    public static ?string $title = "Обработчик кнопок";

    public ?array $data = [];

    public int $id;
    public string $name;

    public function getRecord(): ?Model
    {
        return BotMessageButtonCallback::class;
    }

    public function getTitle(): string|Htmlable
    {
        return $this->name;
    }

    protected function getHeaderActions(): array
    {
        return [];
    }


    public function mount(int $id): void
    {
        $this->id = $id;

        $data = ($id > 0 ? BotMessageButtonCallback::find($id)->toArray() : []);
        $this->name = ($id > 0?$data['name']:'Новая кнопка');

        $this->form->fill($data);

        if(!auth()->user()->hasPermissionTo('Update:BotMessageButtonCallback')) {

            redirect('/admin/bots/access');
        }
    }

    protected function getForms(): array
    {
        return ['form'];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Обработчик кнопок')
                    ->description('Укажите базовые настройки, чтобы продолжить работу')
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ])
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->validationMessages([
                                'required' => 'Обязательно укажите наименование',
                            ])
                            ->disabled(auth()->user()->hasPermissionTo('Update:BotMessageButtonCallback'))
                            ->label('Наименование')
                            ->maxLength(50),
                        TextInput::make('system_name')
                            ->required()
                            ->validationMessages([
                                'required' => 'Обязательно укажите system_name',
                            ])
                            ->disabled(auth()->user()->hasPermissionTo('Update:BotMessageButtonCallback'))
                            ->label('Псевдоним')
                            ->maxLength(50),
                    ]),
                Actions::make([
                    Action::make('Сохранить')
                        ->action(function () {
                            $data = $this->form->getState();


                            if ($this->id>0) {
                                BotMessageButtonCallback::where('id', $this->id)->update($data);
                                $output_id = $this->id;
                            } else {
                                $new = BotMessageButtonCallback::create($data);
                                $output_id = $new->id;
                            }

                            Notification::make()
                                ->title('Данные успешно сохранены!')
                                ->success()
                                ->send();

                            return redirect('/admin/bot-message-button-callbacks');
                        })
                        ->visible(auth()->user()->hasPermissionTo('Create:BotMessageButtonCallback')?true:false),
                    Action::make('Cancel')
                        ->action(function () {
                            return redirect('/admin/admin-bot-message-button-callback');
                        })
                        ->label('Отменить и вернуться назад'),
                ])
            ])->statePath('data');
    }
}


