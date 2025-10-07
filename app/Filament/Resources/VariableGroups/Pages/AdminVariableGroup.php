<?php

namespace App\Filament\Resources\VariableGroups\Pages;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\VariableGroups\VariableGroupResource;
use App\Models\Core\VariableGroup;
use App\Models\Core\VariablesSystem;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;


class AdminVariableGroup extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static string $resource = VariableGroupResource::class;

    protected string $view = 'filament.resources.variable-group-resource.pages.admin-variable-group';


    public static ?string $label = "Группы переменных";
    public static ?string $navigationLabel = "Группы переменных";
    public static ?string $title = "Группы переменных";

    public ?array $data = [];

    public int $id;

    public int $variables_system_types_id;

    public function getRecord(): ?Model
    {
        return VariableGroup::class;
    }

    public function getTitle(): string|Htmlable
    {
        return __('Настройки переменных');
    }

    protected function getHeaderActions(): array
    {
        return [];
    }


    public function mount(int $id): void
    {
        $this->$id = $id;

        $data = ($id>0?VariableGroup::find($id)->toArray():[]);

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
                Section::make('Тип и название группы переменных')
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
                                'required' => 'Обязательно укажите название',
                            ])
                            ->label('Название группы переменных (только в панели администратора)')
                            ->maxLength(255),
                        TextInput::make('description')
                            ->label('Описание')
                            ->maxLength(255),
                        TextInput::make('alias')
                            ->required()
                            ->validationMessages([
                                'required' => 'Обязательно укажите Псевдоним',
                            ])
                            ->label('Псевдоним')
                            ->maxLength(255),
                    ]),
                Actions::make([
                    Action::make('Сохранить')
                        ->action(function () {
                            $data = $this->form->getState();

                            if ($this->id > 0) {
                                VariableGroup::where('id', $this->id)->update($data);
                                $output_id = $this->id;
                            } else {
                                $new = VariableGroup::create($data);
                                $output_id = $new->id;
                            }

                            Notification::make()
                                ->title('Данные успешно сохранены!')
                                ->success()
                                ->send();

                            if ($this->id>0) {
                                return redirect('/admin/variable-groups');
                            } else {
                                return redirect('/admin/variable-groups/' . $output_id . '/admin');
                            }
                        }),
                ])
            ])->statePath('data');
    }


    public function table(Table $table): Table
    {

        return $table
            ->query(VariablesSystem::query()->with('variable_system_variable_system_type')->where('variable_group_id', $this->id))
            ->columns([
                TextColumn::make('name')
                    ->label('Название')
                    ->searchable(),
                TextColumn::make('variable_system_variable_system_type.name')
                    ->label('Тип'),
                ViewColumn::make('status')->view('filament.resources.variable-groups-resource.columns.variable_value')
                    ->label('Значение')
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()->url(fn($record) => "https://app.kovalchuk.tech/admin/variables-systems/".$this->id."/".$record->id."/admin"),
                DeleteAction::make()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                ])
            ]);


    }


}


