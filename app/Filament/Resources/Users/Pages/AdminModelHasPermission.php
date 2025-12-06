<?php

namespace App\Filament\Resources\Users\Pages;
use App\Models\Core\BotBranchLinkProduct;
use App\Models\Core\Product;
use App\Models\Core\ModelHasPermission;
use App\Models\Core\Permission;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\CheckBoxList;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use App\Filament\Resources\Users\UserResource;
use App\Models\Core\User;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;


class AdminModelHasPermission extends Page  implements HasForms,HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static string $resource = UserResource::class;

    protected string $view = 'filament.resources.user-resource.pages.model-has-permission';


    public static ?string $label = "Пользователь";
    public static ?string $navigationLabel = "Пользователь";
    public static ?string $title = "Пользователь";

    public ?array $data = [];

    public ?array $end_by_products = [];
    public ?array $end_by_products_in_branch = [];

    public int $id;

    public array $permissions = [];

    public function getRecord(): ?Model
    {
        return ModelHasPermission::class;
    }

    public function getTitle(): string|Htmlable
    {
        return __('Права');
    }

    protected function getHeaderActions(): array
    {
        return [];
    }


    public function mount(int $id): void
    {
        $this->id = $id;

        $this->end_by_products = Product::all()->pluck('name', 'id')->toArray();
        $this->end_by_products_in_branch = BotBranchLinkProduct::select('product_id')->where('bot_branch_id', $id)->pluck('product_id')->toArray();

        $data = ($id>0?User::find($id)->toArray():[]);

        $this->form->fill($data);


        $modelHasPermissions = ModelHasPermission::query()->with('permissions')->where('model_id', $this->id)
            ->orderByDesc('permission_id')->get();

        foreach ( $modelHasPermissions as $modelHasPermission){
            $this->permissions[] = ['name' => $modelHasPermission->permissions->name, 'model_type' => $modelHasPermission->model_type];
        }    }

    protected function getForms(): array
    {
        return ['form'];
    }

    protected function getTables(): array
    {
        return ['table'];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make("Право")
                    ->description('Основные данные')
                    ->columns([
                        'sm' => 3,
                        'md' => 3,
                        'lg' => 3,
                        'xl' => 3,
                        '2xl' => 3,
                    ])
                    ->schema([
                        Hidden::make('model_id'),
                        Hidden::make('model_type'),

                        Select::make('permisson_id')
                            ->label('Право')
                            ->required()
                            ->validationMessages([
                                'required' => 'Обязательно выберите значение из списка',
                            ])
                            ->options(
                                Permission::query()->pluck('name', 'id')
                            ),

                    ]),

                Actions::make([
                    Action::make('Сохранить')
                          ->visible(auth()->user()->hasPermissionTo('Update:User'))
                        ->action(function () {
                            $data = $this->form->getState();
                            $data['model_id'] = $this->id;
                            $data['model_type'] = 'App\Models\Core\User';

                            ModelHasPermission::create($data);

                            Notification::make()
                                ->title('Данные успешно сохранены!')
                                ->success()
                                ->send();

                            return redirect("/admin/users/".$this->id."/model-has-permission");
                        }),
                    Action::make('Cancel')
                        ->color('gray')
                        ->action(function () {
                            return redirect('/admin/users');
                        })
                        ->label('Отменить и вернуться назад')
                ])
            ])->statePath('data');

    }

    public function table(Table $table): Table
    {
        return $table
            ->records(fn (): array => $this->permissions)
            ->columns([
                TextColumn::make('name')
                    ->label('Право')
                    ->searchable(),
                TextColumn::make('model_type')
                    ->label('Тип модели')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
//                DeleteAction::make()
//                 ->visible(auth()->user()->hasPermissionTo('Delete:User'))

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                     ->visible(auth()->user()->hasPermissionTo('Delete:User'))

                ]),
            ]);
        // ->recordUrl(fn($record) => "/admin/permissions/");
    }
}


