<?php

namespace App\Filament\Resources\Roles\Pages;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\CheckBoxList;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use App\Filament\Resources\Roles\RoleResource;
use App\Models\Core\Role;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;


class AdminRole extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = RoleResource::class;

    protected string $view = 'filament.resources.role-resource.pages.admin-role';


    public static ?string $label = "Пользователь";
    public static ?string $navigationLabel = "Пользователь";
    public static ?string $title = "Пользователь";

    public ?array $data = [];

    public int $id;

    public ?array $roles = [];
    public string $name;

    public function getRecord(): ?Model
    {
        return Role::class;
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
        $data = ($id>0?Role::find($id)->toArray():[]);
        $this->name = ($id > 0?$data['name']:'Новая роль');

        $this->form->fill($data);

        $this->roles = Role::all()->pluck('name', 'id')->toArray();
    }

    protected function getForms(): array
    {
        return ['form'];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Роль')
                    ->description('Укажите базовые настройки, чтобы продолжить работу')
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ])
                    ->schema([
                        Forms\Components\Select::make('roles')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->disabled(auth()->user()->hasPermissionTo('Update:Role')?false:true)
                            ->searchable(),

// Using CheckboxList Component
                        Forms\Components\CheckboxList::make('roles')
                            ->relationship('roles', 'name')
                            ->disabled(auth()->user()->hasPermissionTo('Update:Role')?false:true)
                            ->searchable(),
                        FilamentShieldPlugin::make()
                            ->navigationLabel('Label')                  // string|Closure|null
                            ->navigationIcon('heroicon-o-home')         // string|Closure|null
                            ->activeNavigationIcon('heroicon-s-home')   // string|Closure|null
                            ->navigationGroup('Group')                  // string|Closure|null
                            ->navigationSort(10)                        // int|Closure|null
                            ->navigationBadge('5')                      // string|Closure|null
                            ->navigationBadgeColor('success')           // string|array|Closure|null
                            ->navigationParentItem('parent.item')       // string|Closure|null
                            ->registerNavigation()                     // bool|Closure

        ]),
                Actions::make([
                    Action::make('Сохранить')
                        ->action(function () {
                            $data = $this->form->getState();
                            return redirect('/admin/roles');
                        }) 
                        ->visible(auth()->user()->can('Create:Role')),

                    Action::make('Cancel')
                        ->color('gray')
                        ->action(function () {
                            return redirect('/admin/roles');
                        })
                        ->label('Отменить и вернуться назад')
                ])
            ])->statePath('data');
    }
}


