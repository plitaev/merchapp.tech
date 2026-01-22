<?php

namespace App\Filament\Resources\MiniApps\Pages;
use App\Models\Core\Bot;
use App\Models\Core\MiniAppClass;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\CheckBoxList;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use App\Filament\Resources\MiniApps\MiniAppResource;
use App\Models\Core\MiniApp;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;


class AdminMiniApp extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = MiniAppResource::class;

    protected string $view = 'filament.resources.mini-app-resource.pages.admin-mini-app';


    public static ?string $label = "Мини-приложение";
    public static ?string $navigationLabel = "Мини-приложение";
    public static ?string $title = "Мини-приложение";

    public ?array $data = [];

    public int $id;

    public ?array $roles = [];

    public function getRecord(): ?Model
    {
        return MiniApp::class;
    }

    public function getTitle(): string|Htmlable
    {
        return __('Мини-приложение');
    }

    protected function getHeaderActions(): array
    {
        return [];
    }


    public function mount(int $id): void
    {
        $this->id = $id;
        $data = ($id > 0 ? MiniApp::find($id)->toArray() : []);
        $this->form->fill($data);

        $this->roles = MiniApp::all()->pluck('name', 'id')->toArray();

        if (!auth()->user()->hasPermissionTo('View:MiniApp')) {
            redirect('/admin/bots/access');
        }
    }

    protected function getForms(): array
    {
        return ['form'];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Мини-приложение')
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
                            ->label('Название (Только в панели администратора)')
                            ->required()
                            ->disabled(auth()->user()->hasPermissionTo('Update:MiniApp') ? false : true)
                            ->maxLength(255),
                        TextInput::make('url')
                            ->label('URL')
                            ->required()
                            ->disabled(auth()->user()->hasPermissionTo('Update:MiniApp') ? false : true)
                            ->maxLength(255),
                        Select::make('bot_id')
                            ->label('Привязанный бот')
                            ->required()
                            ->options(Bot::all()->pluck('name', 'id'))
                            ->disabled(auth()->user()->hasPermissionTo('Update:MiniApp') ? false : true)
                            ->searchable(),
                        Select::make('class_id')
                            ->label('Тип мини-приложения')
                            ->required()
                            ->options(MiniAppClass::all()->pluck('name', 'id'))
                            ->disabled(auth()->user()->hasPermissionTo('Update:MiniApp') ? false : true)
                            ->searchable(),
                    ]),
                Actions::make([
                    Action::make('Сохранить')
                        ->action(function () {
                            $data = $this->form->getState();

                            if ($this->id > 0) {
                                MiniApp::where('id', $this->id)->update($data);
                                $output_id = $this->id;
                            } else {
                                $new = MiniApp::create($data);
                                $output_id = $new->id;
                            }

                            Notification::make()
                                ->title('Данные успешно сохранены!')
                                ->success()
                                ->send();

                            return redirect('/admin/mini-apps');
                        })
                        ->visible(auth()->user()->can('Create:MiniApp')),
                    Action::make('Cancel')
                        ->color('gray')
                        ->action(function () {
                            return redirect('/admin/mini-apps');
                        })
                        ->label('Отменить и вернуться назад')
                ])
            ])->statePath('data');
    }
}
