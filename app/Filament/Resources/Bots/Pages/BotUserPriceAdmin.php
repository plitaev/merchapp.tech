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

    public int $bot_id;
    public string $bot_name;

    public function getHeading(): string
    {
        return $this->bot_name;
    }

    public function getTitle(): string
    {
        return $this->bot_name;
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
                            ->options(Product::all()->pluck('name','id'))
                            ->searchable(),
                        // ->disabled(fn() => ['readonly' => auth()->user()->can('Update:BotUserUnBanSchedule')?true:false]),

                        TextInput::make('price')
                            ->label('Цена')
                            ->required()
                            ->maxLength(255)
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

                        }),
                    //   ->disabled(fn() => ['readonly' => auth()->user()->can('Create:BotUserUnBanSchedule')?true:false]),
                    Action::make('Cancel')
                        ->action(function () {
                            return redirect('/admin/bots/' . $this->bot_user_id . '/chat-admin');
                        })
                        ->label('Отменить и вернуться назад')
                ]),

            ])->statePath('data');

    }

}
