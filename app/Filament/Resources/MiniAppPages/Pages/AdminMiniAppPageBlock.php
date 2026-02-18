<?php

namespace App\Filament\Resources\MiniAppPages\Pages;
use App\Filament\Resources\MiniAppPages\MiniAppPageResource;
use App\Models\Core\BotBranchLinkProduct;
use App\Models\Core\MiniAppBlockType;
use App\Models\Core\Product;
use App\Models\Core\MiniAppPageBlock;

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


class AdminMiniAppPageBlock extends Page  implements HasForms,HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static string $resource = MiniAppPageResource::class;

    protected string $view = 'filament.resources.mini-app-page-resource.pages.mini-app-page-blocks';



    public static ?string $label = "Блок страницы";
    public static ?string $navigationLabel = "Блок страницы";
    public static ?string $title = "Блок страницы";

    public ?array $data = [];

    public int $record;

    public function getRecord(): ?Model
    {
        return MiniAppPageBlock::class;
    }

    public function getTitle(): string|Htmlable
    {
        return __('Блок страницы');
    }

    protected function getHeaderActions(): array
    {
        return [];
    }


    public function mount(int $record): void
    {
        $this->record = $record;
        
        $data = ($record>0?MiniAppPageBlock::where('mini_app_page_id', $record)->pluck('mini_app_page_id')->toArray():[]);

        $this->form->fill($data);

    }

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
                Section::make("Блок страницы")
                    ->description('Основные данные')
                    ->columns([
                        'sm' => 3,
                        'md' => 3,
                        'lg' => 3,
                        'xl' => 3,
                        '2xl' => 3,
                    ])
                    ->schema([
                        Hidden::make('mini_app_page_id'),

                        Select::make('block_type_id')
                            ->label('Тип блока')
                            ->required()
                            ->disabled(auth()->user()->hasPermissionTo('Update:User')?false:true)
                            ->validationMessages([
                                'required' => 'Обязательно выберите значение из списка',
                            ])
                            ->options(
                                MiniAppBlockType::query()->pluck('name', 'id')
                            ),
                        TextInput::make('position')
                            ->label('Позиция')
                            ->maxLength(255),

                    ]),

                Actions::make([
                    Action::make('Сохранить')
                        ->visible(auth()->user()->hasPermissionTo('Update:User'))
                        ->action(function () {
                            $data = $this->form->getState();
                            $data['mini_app_page_id'] = $this->record;

                            $new = MiniAppPageBlock::create($data);
                            $output_id = $new->record;
                            
                            Notification::make()
                                ->title('Данные успешно сохранены!')
                                ->success()
                                ->send();

                            return redirect('/admin/mini-app-pages/' . $this->record . '/mini-app-page-blocks');

                        }),
                       // ->visible(auth()->user()->hasPermissionTo('Update:User')),
                    Action::make('Cancel')
                        ->color('gray')
                        ->action(function () {
                            return redirect('/admin/mini-app-pages/' . $this->record . '/mini-app-page-blocks');
                        })
                        ->label('Отменить и вернуться назад')
                ])
            ])->statePath('data');

    }

    public function table(Table $table): Table
    {
        return $table
            ->query(MiniAppPageBlock::query()->with('block_type')->where('mini_app_page_id', $this->record))
            ->columns([
                TextColumn::make('block_type.name')
                    ->label('Тип блока страницы')
                    ->searchable(),
                TextColumn::make('position')
                    ->label('Позиция')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                DeleteAction::make()
                    ->visible(auth()->user()->hasPermissionTo('Delete:MiniApp'))

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                     ->visible(auth()->user()->hasPermissionTo('Delete:MiniApp'))

                ]),
            ]);
    }
}


