<?php

namespace App\Filament\Resources\Users\Pages;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\CheckBoxList;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use App\Filament\Resources\Users\UserResource;
use App\Models\Core\User;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;


class AdminUser extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = UserResource::class;

    protected string $view = 'filament.resources.user-resource.pages.admin-user';


    public static ?string $label = "Пользователь";
    public static ?string $navigationLabel = "Пользователь";
    public static ?string $title = "Пользователь";

    public ?array $data = [];

    public int $id;

    public function getRecord(): ?Model
    {
        return User::class;
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

        $data = ($id>0?User::find($id)->toArray():[]);
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
                        Forms\Components\CheckboxList::make('roles')
                            ->relationship('roles', 'name')
                            ->searchable(),
                    ]),
                Actions::make([
                    Action::make('Сохранить')
                        ->action(function () {
                            $data = $this->form->getState();
                            return redirect('/admin/users');
                        }),
                ])
            ])->statePath('data');
    }
}


