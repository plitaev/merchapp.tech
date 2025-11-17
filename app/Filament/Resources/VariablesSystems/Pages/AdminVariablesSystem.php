<?php
namespace App\Filament\Resources\VariablesSystems\Pages;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use App\Filament\Resources\VariablesSystems\VariablesSystemResource;
use App\Models\Core\VariableGroup;
use App\Models\Core\VariablesSystem;
use App\Models\Core\VariablesSystemCallback\VariablesSystemCallback;
use App\Models\Core\VariableSystemType;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class AdminVariablesSystem extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = VariablesSystemResource::class;

    protected string $view = 'filament.resources.variables-system-resource.pages.admin-variables-system';

    protected static ?string $model = VariablesSystem::class;

    public static ?string $label = "Переменная";
    public static ?string $navigationLabel = "Переменная";
    public static ?string $title = "Переменная";

    public $pos_list;

    public ?array $data = [];
    public ?array $data2 = [];

    public int $id;
    public int $variable_group_id;
    public int $variable_system_type_id;
    public function getRecord(): ?Model
    {
        return VariablesSystem::class;
    }

    public function getTitle(): string|Htmlable
    {
        return __('Настройки переменной');
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function mount(int $id, int $variable_group_id): void
    {
        $this->id = $id;
        $this->variable_group_id = $variable_group_id;
        if (auth()->user()->hasPermissionTo('Edit:VariablesSystem')) {

        $data = ($variable_group_id>0?VariablesSystem::with('variable_system_variable_system_type')->with('variable_system_variable_group')->find($variable_group_id)->toArray():["variable_group_id" => $this->id, "variable_system_type_id" => 1]);

        $this->form->fill($data);

        }else{
            redirect('/admin/bots/access');
        }
    }

    public function getSubNavigation(): array
    {
        return [];
    }

    protected function getForms(): array
    {
        return ['form'];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Тип группы, которую видит пользователь')
                    ->description('Укажите основные настройки, чтобы продолжить работу')
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ])
                    ->schema([
                        Hidden::make('id'),
                        Select::make('variable_group_id')
                            ->label('Группа')
                            ->required()
                            ->disabled(auth()->user()->hasPermissionTo('Update:VariablesSystem')?false:true)
                            ->options(VariableGroup::all()->pluck('name', 'id'))
                            ->searchable()
                            ->live(),
                        TextInput::make('name')
                            ->required()
                            ->disabled(auth()->user()->hasPermissionTo('Update:VariablesSystem')?false:true)
                            ->label('Наименование')
                            ->maxLength(255),
                        Select::make('variable_system_type_id')
                            ->label('Тип значения')
                            ->disabled(auth()->user()->hasPermissionTo('Update:VariablesSystem')?false:true)
                            ->required()
                            ->options(VariableSystemType::all()->pluck('name', 'id'))
                            ->searchable()
                            ->live(),
                    ]),
                Section::make('Текст')
                    ->description('Текст, который будет записываться в значение')
                    ->schema([
                        Textarea::make('value_text')
                            ->label('Текст')
                            ->disabled(auth()->user()->hasPermissionTo('Update:VariablesSystem')?false:true)

                    ])
                    ->visible(function (Get $get) {
                        if (is_callable($get)) {
                            return $get('variable_system_type_id') == 3;
                        }
                    }),
                Section::make('Строковый')
                    ->description('Строка, которая будет записываться в значение')
                    ->schema([
                        Textarea::make('value_string')
                            ->label('Строковый тип')
                            ->disabled(auth()->user()->hasPermissionTo('Update:VariablesSystem')?false:true)

                    ])
                    ->visible(function (Get $get) {
                        if (is_callable($get)) {
                            return $get('variable_system_type_id') == 1;
                        }
                    }),
                Section::make('Числовой')
                    ->description('Число, которое будет записываться в значение')
                    ->schema([
                        TextInput::make('value_integer')
                            ->label('Числовой тип')
                            ->disabled(auth()->user()->hasPermissionTo('Update:VariablesSystem')?false:true)

                    ])
                    ->visible(function (Get $get) {
                        if (is_callable($get)) {
                            return $get('variable_system_type_id') == 2;
                        }
                    }),
                Section::make('Дата')
                    ->description('Дата, которая будет записываться в значение')
                    ->schema([
                        DatePicker::make('value_date')
                            ->label('Дата')
                            ->disabled(auth()->user()->hasPermissionTo('Update:VariablesSystem')?false:true)
                    ])
                    ->visible(function (Get $get) {
                        if (is_callable($get)) {
                            return $get('variable_system_type_id') == 4;
                        }
                    }),

                Actions::make([
                    Action::make('Сохранить')
                        ->action(function () {
                            $data = $this->form->getState();

                            if ($this->variable_group_id>0) {
                                VariablesSystem::where('id', $this->variable_group_id)->update($data);
                                $variables_system_id = $this->variable_group_id;
                            } else {
                                $new_variables_system_id = VariablesSystem::create($data);
                                $variables_system_id = $new_variables_system_id->id;
                            }

                            //=========================================================================

                            Notification::make()
                                ->title('Данные успешно сохранены!')
                                ->success()
                                ->send();

                            return redirect('/admin/variables-systems/'.$this->id.'/'.$this->variable_group_id.'/admin');

                        })
                        ->visible(auth()->user()->can('Create:VariablesSystem')),

                    Action::make('Cancel')
                        ->color('gray')
                        ->action(function () {
                            return redirect('/admin/variable-systems');
                        })
                        ->label('Отменить и вернуться назад')
                ])
            ])->statePath('data');
    }

}
