<?php
namespace App\Filament\Resources\BotResource\Pages;

use App\Filament\Resources\BotResource;
use App\Models\Core\Bot;
use App\Models\Core\Product;
use App\Models\Core\ProductType;
use Filament\Forms;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;


class BotProductAdmin extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = BotResource::class;

    protected static string $view = 'filament.resources.bot-resource.pages.bot-product-admin';

    protected static ?string $model = Product::class;

    public static ?string $label = "Тариф";
    public static ?string $navigationLabel = "Тариф";
    public static ?string $title = "Тариф";

    public ?array $data = [];

    public int $bot_id;

    public string $bot_name;

    public int $id;

    public string $name;

    public function getRecord(): ?Model
    {
        return Product::class;
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
            $data = Product::find($id)->toArray();
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
            return "Редактировать тариф";
        } else {
            return "Добавить тариф";
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

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Параметры тарифа')->description('Укажите базовые настройки, чтобы продолжить работу')
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ])
                    ->schema([
                        Forms\Components\Hidden::make('bot_id'),
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->validationMessages([
                                'required' => 'Обязательно укажите название',
                            ])
                            ->label('Название')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('price')
                            ->required()
                            ->validationMessages([
                                'required' => 'Обязательно укажите стоимость',
                            ])
                            ->label('Стоимость')
                            ->maxLength(50),
                        Forms\Components\TextInput::make('days')
                            ->required()
                            ->validationMessages([
                                'required' => 'Обязательно укажите дни',
                            ])
                            ->label('Дни')
                            ->maxLength(50),
                        Forms\Components\Select::make('product_type_id')
                            ->label('Тип продукта')
                            ->required()
                            ->options(ProductType::all()->pluck('name', 'id'))
                            ->searchable()
                            ->live(),
                    ]),
                Section::make('Текст')
                    ->description('Описание продукта')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->label('Описание')
                    ]),
                Actions::make([
                    Action::make('Сохранить')
                        ->action(function () {
                            $data = $this->form->getState();

                            if ($this->id>0) {

                                Product::where('id', $this->id)->update($data);
                            } else {
                                Product::create($data);
                            }

                            Notification::make()
                                ->title('Данные успешно сохранены!')
                                ->success()
                                ->send();

                            return redirect('/admin/bots/'.$this->bot_id.'/products');
                        }),
                    Action::make('Cancel')
                        ->action(function () {
                            return redirect('/admin/bots/'.$this->bot_id.'/products');
                        })
                        ->label('Отменить и вернуться назад')

                ]),
            ])
            ->statePath('data');
    }

}
