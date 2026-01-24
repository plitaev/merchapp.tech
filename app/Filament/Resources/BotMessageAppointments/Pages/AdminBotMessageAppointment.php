<?php

namespace App\Filament\Resources\BotMessageAppointments\Pages;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use App\Filament\Resources\BotMessageAppointments\BotMessageAppointmentResource;
use App\Models\Core\BotMessageAppointment;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;

class AdminBotMessageAppointment extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = BotMessageAppointmentResource::class;

    protected string $view = 'filament.resources.bot-message-appointment-resource.pages.admin-bot-message-appointment';

    public static ?string $label = "Назначение";
    public static ?string $navigationLabel = "Назначение";
    public static ?string $title = "Назначение";

    public ?array $data = [];
    public string $name;

    public int $id;

    public function getRecord(): ?Model
    {
        return BotMessageAppointment::class;
    }

    public function getTitle(): string|Htmlable
    {
        return $this->name;
    }

    protected function getForms(): array
    {
        return ['form'];
    }

    public function getHeading(): string
    {
        if ($this->id > 0) {
            return "Редактировать назначение";
        } else {
            return "Добавить назначение";
        }
    }

    protected function getHeaderActions(): array
    {
        return [];
    }


    public function mount(int $id): void
    {
        $this->id = $id;

        $data = ($id > 0 ? BotMessageAppointment::find($id)->toArray() : []);
        $this->name = ($id > 0?$data['name']:'Новое назначение');

        $this->form->fill($data);

        if (!Auth::user()->hasPermissionTo('Update:BotMessageAppointment')) {
            redirect('/admin/bots/access');
        }

    }



    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Основные параметры')
                    ->description('Укажите основные настройки и заполните все поля')
                    ->columns([
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ])
                    ->schema([
                        TextInput::make('name')
                            ->label('Название (Только в панели администратора)')
                            ->required()
                            ->disabled(auth()->user()->hasPermissionTo('Update:BotMessageAppointment')?false:true)
                            ->maxLength(255),
                        TextInput::make('alias')
                            ->label('Псевдоним')
                            ->required()
                            ->disabled(auth()->user()->hasPermissionTo('Update:BotMessageAppointment')?false:true)
                            ->maxLength(255),
                        Actions::make([
                            Action::make('Сохранить')
                                ->action(function () {
                                    $data = $this->form->getState();

                                    if ($this->id>0) {
                                        BotMessageAppointment::where('id', $this->id)->update($data);
                                       // $output_id = $this->id;
                                    } else {
                                        $new = BotMessageAppointment::create($data);
                                        $output_id = $new->id;
                                    }

                                    Notification::make()
                                        ->title('Данные успешно сохранены!')
                                        ->success()
                                        ->send();

                                   // return redirect('/admin/bot-message-appointments/{{4}}/admin');
                                })
                                ->visible(auth()->user()->can('Create:BotMessageAppointment')),

                        ])
                    ])
            ])->statePath('data');
    }

}

