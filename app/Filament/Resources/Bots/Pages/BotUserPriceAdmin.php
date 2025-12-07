<?php
namespace App\Filament\Resources\Bots\Pages;

use App\Models\Core\BotUserPrice;
use App\Models\Core\Product;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Checkbox;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use App\Filament\Resources\Bots\BotResource;
use App\Models\Core\Bot;
use App\Models\Core\BotUser;
use App\Models\Core\TelegramSupergroup;
use App\Models\Core\TelegramSupergroupLinkBot\TelegramSupergroupLinkBot;
use App\Models\Core\User;

use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use App\Models\Core\SupergroupDeleteParameter;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Support\Facades\Auth;
class BotUserPriceAdmin extends Page implements HasForms
{
    protected static string $resource = BotResource::class;

    protected string $view = 'filament.resources.bot-resource.pages.bot-user-price-admin';

    public static ?string $label = "";
    public static ?string $navigationLabel = "Индивидуальная цена";
    public static ?string $title = "Индивидуальная цена";

    public ?array $data = [];

    public int $id;

    public int $bot_id;
    public string $bot_name;

    public int $bot_user_id;

    public array $bot_user_individual_products;

    public function getHeading(): string
    {
        return $this->bot_name;
    }

    public function getTitle(): string
    {
        return $this->bot_name;
    }

    public function mount(int $bot_id, int $bot_user_id, int $id): void
    {
        if (!Auth::user()->hasPermissionTo('View:Pay')) redirect('/admin/bots/access');

        $this->id = $id;

        $bot = Bot::select('name')->find($bot_id);
        $this->bot_id = $bot_id;
        $this->bot_name = $bot->name;

        $this->bot_user_id = $bot_user_id;

        if ($id > 0) {
            $bot_user_price = BotUserPrice::find($id);
            $bot_user_individual_products = BotUserPrice::select('product_id')->where('bot_user_id', $bot_user_id)->pluck('product_id')->toArray();
            $bot_user_individual_products[] = $bot_user_price->product_id;

            $data = $bot_user_price->toArray();
        } else {
            $bot_user_individual_products = BotUserPrice::select('product_id')->where('bot_user_id', $bot_user_id)->pluck('product_id')->toArray();
            $data = [];
        }

        $this->bot_user_individual_products = $bot_user_individual_products;
        $this->form->fill($data);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Индивидуальная  цена')
                    ->description('Укажите цены, чтобы продолжить работу')
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                        '2xl' => 1,
                    ])
                    ->schema([
                        Hidden::make('id'),
                        Hidden::make('bot_user_id'),

                        Forms\Components\Select::make('product_id')
                            ->label('Продукт')
                            ->options(Product::whereNotIn('id', $this->bot_user_individual_products)->pluck('name','id'))
                            ->searchable()
                            ->disabled(auth()->user()->can('Update:Pay')?false:true),

                        TextInput::make('price')
                            ->label('Цена')
                            ->maxLength(10)
                        //
                    ]),

                Actions::make([
                    Action::make('Сохранить')
                        ->action(function () {
                            $data = $this->form->getState();

                            $data['bot_user_id'] = $this->bot_user_id;

                            if ($this->id > 0) {
                                $data['id'] = $this->id;
                                BotUserPrice::where('id', $this->id)->update($data);
                            }else{
                                $new_bot_user_prices = BotUserPrice::create($data);
                            }

                            Notification::make()
                                ->title('Данные успешно сохранены!')
                                ->success()
                                ->send();

                            if ($this->id>0) {
                                return redirect('/admin/bots/' . $this->bot_user_id .'/'.$this->id. '/bot-users-prices');
                            }else{
                                return redirect('/admin/bots/' . $this->bot_user_id . '/bot-chats');
                            }

                        })
                        ->disabled(auth()->user()->can('Create:Pay')?false:true),
                    Action::make('Cancel')
                        ->action(function () {
                            return redirect('/admin/bots/'.$this->bot_id.'/'.$this->bot_user_id.'/user-prices');
                        })
                        ->label('Отменить и вернуться назад')
                ]),

            ])->statePath('data');

    }

}
