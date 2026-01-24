<?php

namespace App\Filament\Resources\FunnelConditions\Pages;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use App\Filament\Resources\FunnelConditions\FunnelConditionResource;
use App\Models\Core\FunnelCondition;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class AdminFunnelCondition extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = FunnelConditionResource::class;

    protected string $view = 'filament.resources.funnel-condition-resource.pages.admin-funnel-condition';


    public static ?string $label = "Условие воронки";
    public static ?string $navigationLabel = "Условие воронки";
    public static ?string $title = "Условие воронки";

    public ?array $data = [];
    public string $name;

    public int $id;

    public function getRecord(): ?Model
    {
        return FunnelCondition::class;
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
        if (auth()->user()->hasPermissionTo('Update:FunnelCondition')) {

            $data = ($id > 0 ? FunnelCondition::find($id)->toArray() : []);
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
                Section::make('Условие воронки')
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
                            ->disabled(auth()->user()->hasPermissionTo('Update:FunnelCondition')?false:true)
                            ->maxLength(50),
                        TextInput::make('alias')
                            ->required()
                            ->validationMessages([
                                'required' => 'Обязательно укажите псевдоним',
                            ])
                            ->disabled(auth()->user()->hasPermissionTo('Update:FunnelCondition')?false:true)
                            ->label('Псевдоним')
                            ->maxLength(50),
                        ]),
                Actions::make([
                    Action::make('Сохранить')
                        ->action(function () {
                            $data = $this->form->getState();


                            if ($this->id>0) {
                                FunnelCondition::where('id', $this->id)->update($data);
                                $output_id = $this->id;
                            } else {
                                $new = FunnelCondition::create($data);
                                $output_id = $new->id;
                            }

                            Notification::make()
                                ->title('Данные успешно сохранены!')
                                ->success()
                                ->send();

                            return redirect('/admin/funnel-conditions');
                        })
                        ->visible(auth()->user()->can('Create:FunnelCondition')),
                    Action::make('Cancel')
                        ->color('gray')
                        ->action(function () {
                            return redirect('/admin/funnel-conditions');
                        })
                        ->label('Отменить и вернуться назад')

                ])
            ])->statePath('data');
    }
}

