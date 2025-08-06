<?php

namespace App\Filament\Resources\FunnelMessageResource\Pages;

use App\Filament\Resources\FunnelMessageResource;
use App\Models\Core\BotMessage;
use App\Models\Core\Funnel;
use App\Models\Core\FunnelCondition;
use App\Models\Core\FunnelMessage;
use Filament\Forms;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class AdminFunnelMessage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = FunnelMessageResource::class;

    protected static string $view = 'filament.resources.funnel-message-resource.pages.admin-funnel-message';


    public static ?string $label = "Сообщения в воронке";
    public static ?string $navigationLabel = "Сообщения в воронке";
    public static ?string $title = "Сообщения в воронке";

    public ?array $data = [];

    public int $id;

    public function getRecord(): ?Model
    {
        return FunnelMessage::class;
    }

    public function getTitle(): string|Htmlable
    {
        return __('Настройки воронки сообщений');
    }

    protected function getHeaderActions(): array
    {
        return [];
    }


    public function mount(int $id): void
    {
        $this->id = $id;

        $data = ($id>0?FunnelMessage::with('funnel')->with('bot_message')->with('funnel_condition')->find($id)->toArray():["funnel_id" => 1, "bot_message_id" => 1, "funnel_condition_id" => 1]);
        $this->form->fill($data);
    }

    protected function getForms(): array
    {
        return ['form'];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Сообщения в воронке')
                    ->description('Укажите базовые настройки, чтобы продолжить работу')
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ])
                    ->schema([
                        Section::make('Выберите воронку')
                            ->description('')
                            ->schema([
                                Select::make('funnel_id')
                                    ->label('Воронка')
                                    ->required()
                                    ->options(
                                        Funnel::query()->pluck('name', 'id')
                                    )
                                    ->searchable()
                            ]),
                        Select::make('funnel_condition_id')
                            ->label('Состояние воронки')
                            ->required()
                            ->options(
                                FunnelCondition::query()->pluck('name', 'id')
                            )
                            ->searchable(),
                        Select::make('bot_message_id')
                            ->label('Бот сообщений')
                            ->required()
                            ->options(
                                BotMessage::query()->pluck('name', 'id')
                            )
                            ->searchable(),

                    ]),

                Forms\Components\TextInput::make('days_before_condition')
                    ->label('Дни до состояния')
                    ->maxLength(50),
                Forms\Components\TextInput::make('hours_before_condition')
                    ->label('Часы до состояния')
                    ->maxLength(50),
                Forms\Components\TextInput::make('minutes_before_condition')
                    ->label('Минуты до состояния')
                    ->maxLength(50),
                Forms\Components\TextInput::make('days_after_condition')
                    ->label('Дни после состояния')
                    ->maxLength(50),
                Forms\Components\TextInput::make('hours_after_condition')
                    ->label('Часы после состояния')
                    ->maxLength(50),
                Forms\Components\TextInput::make('minutes_after_condition')
                    ->label('Минуты после состояния')
                    ->maxLength(50),
                Actions::make([
                    Action::make('Сохранить')
                        ->action(function () {
                            $data = $this->form->getState();

                            if ($this->id>0) {
                                FunnelMessage::where('id', $this->id)->update($data);
                                $output_id = $this->id;
                            } else {
                                $new = FunnelMessage::create($data);
                                $output_id = $new->id;
                            }

                            Notification::make()
                                ->title('Данные успешно сохранены!')
                                ->success()
                                ->send();

                            return redirect('/admin/funnel-messages/'.$output_id.'/admin');
                        }),
                ])
            ])->statePath('data');
    }
}

