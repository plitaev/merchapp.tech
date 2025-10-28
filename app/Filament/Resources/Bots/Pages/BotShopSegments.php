<?php
namespace App\Filament\Resources\Bots\Pages;

use App\Models\Core\BotBranch;
use App\Models\Core\BotBranchAccess;
use App\Models\Core\BotBranchLinkProduct;
use App\Models\Core\BotMessageAppointment;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\DeleteAction;
use App\Models\Core\BotMessage;
use App\Models\Core\TelegramSendMessageSchedule;
use Illuminate\Support\HtmlString;

use App\Filament\Resources\Bots\BotResource;

use Filament\Forms;
use Filament\Forms\Components;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Schemas\Components\Utilities\Get;

use App\Actions\Core\Sending\SendingSave;
use App\Actions\Core\BotBranch\BotBranchSetEndByProducts;

use App\Models\Core\Bot;
use App\Models\Core\Product;
use App\Models\Core\SendingAppointment;
use App\Models\Core\SendingButton;
use App\Models\Core\SendingListener;
use App\Models\Core\SendingType;


class BotShopSegments extends Page implements HasForms, HasTable, HasInfolists
{
    use InteractsWithForms;
    use InteractsWithInfolists;
    use InteractsWithTable;

    protected static string $resource = BotResource::class;

    protected string $view = 'filament.resources.bot-resource.pages.bot-shop-segments';

    public int $id;

    public int $bot_id;
    public int $bot_message_id;
    public string $bot_name;
    public string $bot_alias;
    public string $bot_branch_hash;
    public int $sent_users;
    public int $new_bot_id;

    protected static ?string $model = BotBranch::class;

    public static ?string $label = "Акция";
    public static ?string $navigationLabel = "Акция";
    public static ?string $title = "Акция";
    public ?array $data = [];
    public ?array $data_bot_message_link_listener = [];
    public ?array $data_bot_user = [];

    public ?array $end_by_products = [];
    public ?array $end_by_products_in_branch = [];

    public ?array $end_by_products2 = [];
    public ?array $end_by_products_in_branch2 = [];

    public function getRecord(): ?Model
    {
        return BotBranch::class;
    }

    public function getHeading(): string
    {
        return "Добавить по покупкам";
    }

    protected function getHeaderActions(): array
    {
        return [];
    }


    public function mount(int $bot_id, int $id): void
    {
        $this->bot_id = $bot_id;
        $this->id = $id;

        $this->end_by_products = Product::all()->pluck('name', 'id')->toArray();
        $this->end_by_products_in_branch = BotBranchLinkProduct::select('product_id')->where('bot_branch_id', $id)->pluck('product_id')->toArray();


        //$this->end_by_products2 = Product::all()->pluck('name', 'id')->toArray();
        $this->end_by_products_in_branch2 = BotBranchLinkProduct::select('product_id')->where('bot_branch_id', $id)->whereNotIn('product_id',$this->end_by_products_in_branch)->pluck('product_id')->toArray();

//        $this->end_by_products_in_branch2 = Product::join('bot_branch_link_products', 'bot_branch_link_products.product_id', '=', 'products.id')
//        ->whereNotIn('product_id', $this->end_by_products_in_branch)
//            ->where('bot_branch_id', $id)
//            ->pluck('product_id')->toArray();
        //BotBranchLinkProduct::select('product_id')->where('bot_branch_id', $id)->pluck('product_id')->toArray();


        $data = [];
        $data['bot_id'] = $bot_id;
        $this->bot_branch_hash = '';


        $bot = Bot::select('name', 'alias')->find($bot_id);
        $this->bot_name = $bot->name;
        $this->bot_alias = $bot->alias;

        $this->form->fill($data);
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
                Section::make('Купил')
                    ->description('Укажите, продукты, которые пользователь покупал')
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                        '2xl' => 1,
                    ])
                    ->schema([
                        Forms\Components\CheckboxList::make('end_by_products')
                            ->label('По покупке продуктов')
                            ->options($this->end_by_products)
                            ->afterStateHydrated(function ($component, $state) {
                                if (! filled($state)) {
                                    $component->state($this->end_by_products_in_branch);
                                }
                            })
                    ]),
                Section::make('Не Купил')
                    ->description('Укажите, продукты, которые пользователь не купил')
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                        '2xl' => 1,
                    ])
                    ->schema([
                        Forms\Components\CheckboxList::make('end_by_products')
                            ->label('По покупке продуктов')
                            ->options($this->end_by_products)
                            ->afterStateHydrated(function ($component, $state) {
                                if (! filled($state)) {
                                    $component->state(!$this->end_by_products_in_branch2);
                                }
                            })
                    ]),

                Actions::make([
                    Action::make('Сохранить')
                        ->action(function () {

                            $botBranchSetEndByProducts = new BotBranchSetEndByProducts();

                            $data = $this->form->getState();
                            $end_by_products = $data['end_by_products'];
                            unset($data['end_by_products']);

                            $hash=hash('sha256', $data['alias']);

                            if ($this->id > 0) {
                                $branch = BotBranch::find($this->id);
                                if (!$branch->hash) {
                                    $data['hash'] = $hash;
                                }

                                BotBranch::where('id', $this->id)->update($data);
                                $botBranchSetEndByProducts->handle($this->id, $end_by_products);
                                return redirect('/admin/bots/'.$this->bot_id.'/branches');

                            } else {
                                $data['hash'] = $hash;
                                $new = BotBranch::create($data);
                                $botBranchSetEndByProducts->handle($new->id, $end_by_products);

                                return redirect('/admin/bots/'.$this->bot_id.'/'.$new->id.'/branch-admin');
                            }

                        }),
                    Action::make('Cancel')
                        ->action(function () {
                            return redirect('/admin/bots/'.$this->bot_id.'/branches');
                        })
                        ->label('Вернуться назад'),
                    Action::make('Stop')
                        ->action(function () {
                            return redirect('/admin/bots/'.$this->bot_id.'/branches');
                        })
                        ->label('Завершить акцию сейчас')
                ])
            ])->statePath('data');
    }

}
