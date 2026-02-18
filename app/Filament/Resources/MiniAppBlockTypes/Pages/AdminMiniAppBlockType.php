<?php

namespace App\Filament\Resources\MiniAppBlockTypes\Pages;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use App\Filament\Resources\MiniAppBlockTypes\MiniAppBlockTypeResource;
use App\Models\Core\MiniAppBlockType;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class AdminMiniAppBlockType extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = MiniAppBlockTypeResource::class;

    protected string $view = 'filament.resources.mini-app-block-type-resource.pages.admin-mini-app-block-type';


    public static ?string $label = "Тип блока Мини-приложения";
    public static ?string $navigationLabel = "Тип блока Мини-приложения";
    public static ?string $title = "Тип блока Мини-приложения";

    public ?array $data = [];
    public string $name;

    public int $id;

    public function getRecord(): ?Model
    {
        return MiniAppBlockType::class;
    }

    public function getTitle(): string|Htmlable
    {
        return $this->name;
    }

    public function getHeading(): string
    {
        if ($this->id > 0) {
            return "Редактировать тип блока Мини-приложения";
        } else {
            return "Добавить тип блока Мини-приложения";
        }
    }

    protected function getHeaderActions(): array
    {
        return [];
    }


    public function mount(int $id): void
    {
        $this->id = $id;
        if (auth()->user()->hasPermissionTo('Update:MiniAppBlockType')) {

            $data = ($id > 0 ? MiniAppBlockType::find($id)->toArray() : []);
            $this->name = ($id > 0?$data['name']:'Новая воронка');

            $this->form->fill($data);
        }else{
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
                Section::make('Тип блока Мини-приложения')
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
                            ->label('Наименование')
                            ->disabled(auth()->user()->hasPermissionTo('Update:MiniAppBlockType')?false:true)
                            ->maxLength(50),
                        TextInput::make('alias')
                            ->required()
                            ->validationMessages([
                                'required' => 'Обязательно укажите псевдоним',
                            ])
                            ->disabled(auth()->user()->hasPermissionTo('Update:MiniAppBlockType')?false:true)
                            ->label('Псевдоним')
                            ->maxLength(50),
                        ]),
                Actions::make([
                    Action::make('Сохранить')
                        ->action(function () {
                            $data = $this->form->getState();


                            if ($this->id>0) {
                                MiniAppBlockType::where('id', $this->id)->update($data);
                                $output_id = $this->id;
                            } else {
                                $new = MiniAppBlockType::create($data);
                                $output_id = $new->id;
                            }

                            Notification::make()
                                ->title('Данные успешно сохранены!')
                                ->success()
                                ->send();

                            return redirect('/admin/mini-app-block-types');
                        })
                        ->visible(auth()->user()->can('Create:MiniAppBlockType')),
                    Action::make('Cancel')
                        ->color('gray')
                        ->action(function () {
                            return redirect('/admin/mini-app-block-types');
                        })
                        ->label('Отменить и вернуться назад')

                ])
            ])->statePath('data');
    }
}

