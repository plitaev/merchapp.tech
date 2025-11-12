<?php
namespace App\Filament\Resources\Bots\Pages;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use App\Filament\Resources\Bots\BotResource;
use App\Models\Core\Bot;
use App\Models\Core\Funnel;
use App\Models\Core\User;

use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;


class BotFunnelAdmin extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = BotResource::class;

    protected string $view = 'filament.resources.bot-resource.pages.bot-product-admin';

    protected static ?string $model = Funnel::class;

    public static ?string $label = "Воронка";
    public static ?string $navigationLabel = "Воронка";
    public static ?string $title = "Воронка";

    public ?array $data = [];

    public int $bot_id;

    public string $bot_name;

    public int $id;

    public string $name;

    public function getRecord(): ?Model
    {
        return Funnel::class;
    }

    protected function getHeaderActions(): array
    {
        return [];
    }


    public function mount(int $bot_id, int $id): void
    {
        $this->bot_id = $bot_id;

        $this->id = $id;

        if ($id > 0) {
            $data = Funnel::find($id)->toArray();
        } else {
            $data = [];
            $data['bot_id'] = $bot_id;
        }

        $bot = Bot::select('name')->find($bot_id);
        $this->bot_name = $bot->name;

        $this->form->fill($data);
    }

    public function getHeading(): string
    {
        if ($this->id > 0) {
            return "Редактировать воронку";
        } else {
            return "Добавить воронку";
        }
    }

    public function getTitle(): string
    {
        return $this->bot_name;
    }

    protected function getForms(): array
    {
        return ['form'];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Воронка')
                    ->description('Укажите базовые настройки, чтобы продолжить работу')
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ])
                    ->schema([
                        Hidden::make('bot_id'),

                        TextInput::make('name')
                            ->required()
                            ->validationMessages([
                                'required' => 'Обязательно укажите наименование',
                            ])
                            ->label('Наименование')
                            ->maxLength(50)
                            ->disabled(auth()->user()->hasPermissionTo('Update:Funnel')?false:true),
                        TextInput::make('alias')
                            ->required()
                            ->validationMessages([
                                'required' => 'Обязательно укажите псевдоним',
                            ])
                            ->label('Псевдоним')
                            ->maxLength(50)
                            ->disabled(auth()->user()->hasPermissionTo('Update:Funnel')?false:true),
                    ]),
                Actions::make([
                    Action::make('Сохранить')
                        ->action(function () {
                            $data = $this->form->getState();

                            if ($this->id>0) {

                                Funnel::where('id', $this->id)->update($data);
                            } else {
                                Funnel::create($data);
                            }

                            Notification::make()
                                ->title('Данные успешно сохранены!')
                                ->success()
                                ->send();

                            return redirect('/admin/bots/' . $this->bot_id .'/funnels');
                        })
                        ->visible(auth()->user()->hasPermissionTo('Create:Funnel')?false:true),

                    Action::make('Cancel')
                        ->action(function () {
                            return redirect('/admin/bots/'.$this->bot_id.'/funnels');
                        })
                        ->label('Отменить и вернуться назад')
                ]),
            ])->statePath('data');
    }

}
