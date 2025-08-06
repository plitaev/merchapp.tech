<?php

namespace App\Filament\Resources\ListenerResource\Pages;
use App\Filament\Resources\ListenerResource;
use App\Models\Core\Listener;
use Filament\Forms;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;


class AdminListener extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = ListenerResource::class;

    protected static string $view = 'filament.resources.listener-resource.pages.admin-listener';


    public static ?string $label = "Ожидание";
    public static ?string $navigationLabel = "Ожидание";
    public static ?string $title = "Ожидание";

    public ?array $data = [];

    public int $id;

    public function getRecord(): ?Model
    {
        return Listener::class;
    }

    public function getTitle(): string|Htmlable
    {
        return __('Ожидание');
    }

    protected function getHeaderActions(): array
    {
        return [];
    }


    public function mount(int $id): void
    {
        $this->id = $id;

        $data = ($id>0?Listener::find($id)->toArray():[]);
        $this->form->fill($data);
    }

    protected function getForms(): array
    {
        return ['form'];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Ожидание')
                    ->description('Укажите базовые настройки, чтобы продолжить работу')
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ])
                    ->schema([
                        Section::make('Основные сведения')
                            ->description('')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->validationMessages([
                                        'required' => 'Обязательно укажите наименование',
                                    ])
                                    ->label('Наименование')
                                    ->maxLength(50),
                                Forms\Components\TextInput::make('alias')
                                    ->required()
                                    ->validationMessages([
                                        'required' => 'Обязательно укажите alias',
                                    ])
                                    ->label('Alias')
                                    ->maxLength(50),
                            ]),

                    ]),
                Actions::make([
                    Action::make('Сохранить')
                        ->action(function () {
                            $data = $this->form->getState();


                            if ($this->id>0) {
                                Listener::where('id', $this->id)->update($data);
                                $output_id = $this->id;
                            } else {
                                $new = Listener::create($data);
                                $output_id = $new->id;
                            }

                            Notification::make()
                                ->title('Данные успешно сохранены!')
                                ->success()
                                ->send();

                            return redirect('/admin/listeners');
                        }),
                ])
            ])->statePath('data');
    }
}


