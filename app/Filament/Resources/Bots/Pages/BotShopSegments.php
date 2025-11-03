<?php

namespace App\Filament\Resources\Bots\Pages;

use App\Actions\Core\BotUser\BotUserGetByEmail;
use App\Models\Core\BotBranch;
use App\Models\Core\BotBranchAccess;
use App\Models\Core\BotBranchLinkProduct;
use App\Models\Core\BotMessageAppointment;
use App\Models\Core\BotUser;
use App\Models\Core\Pay;
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
use Filament\Schemas\Components\Utilities\Set;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Illuminate\Validation\Rules\Exists;


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
    public string $bot_name;

    public ?array $data = [];
    public ?array $all_product = [];
    public ?array $no_all_product = [];
    public ?array $no_all_product2 = [];


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

        $this->all_product = Product::all()->where('bot_id', $this->bot_id)->pluck('name', 'id')->toArray();

        $data = [];
        $data['bot_id'] = $bot_id;
        $this->bot_branch_hash = '';

        $bot = Bot::select('name', 'alias')->find($bot_id);
        $this->bot_name = $bot->name;

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
                Section::make('Добавить по покупкам')
                    ->schema([

                        Wizard::make([

                            Step::make('Купил')
                                ->schema([
                                    Forms\Components\CheckboxList::make('all_product')
                                        ->label('По покупке продуктов')
                                        ->options($this->all_product)
                                ])
                                ->afterValidation(function (Set $set) {
                                    $data = $this->form->getState();

                                    if (is_callable($set)) {
                                        $products_step2 = Product::all()->where('bot_id', $this->bot_id)->whereNotIn('id', $data['all_product'])->pluck('name', 'id')->toArray();
                                        $set('products_step2', $products_step2);
                                    }

                                }),

                            Step::make('Не купил')

                                ->schema([
                                    Forms\Components\CheckboxList::make('no_all_product')
                                        ->label('По не покупке продуктов')
                                        ->options(fn($get) => $get('products_step2') ?: [])
                                        ->afterStateHydrated(function ($component, $state) {
                                            if (! filled($state)) {
                                                $component->state($this->no_all_product);
                                            }
                                        }),

                                    Actions::make([
                                        Action::make('Сохранить')
                                            ->action(function () {
                                                $data = $this->form->getState();
                                                $bot_users = Pay::select('bot_user_id');

                                                if($data['all_product'] && !$data['no_all_product']) {
                                                    $bot_users = $bot_users
                                                        ->where('product_id', $data['all_product'])
                                                        ->get();
                                                } elseif ($data['no_all_product'] && !$data['all_product']) {
                                                    $bot_users = $bot_users
                                                        ->whereNotIn('product_id', $data['no_all_product'])
                                                        ->get();
                                                }elseif($data['no_all_product'] && $data['no_all_product']) {
                                                    $bot_users = $bot_users
                                                        ->where('product_id', $data['all_product'])
                                                        ->whereNotIn('product_id', $data['no_all_product'])
                                                        ->get();
                                                }

                                                if($bot_users) {
                                                    foreach ($bot_users as $bot_user) {
                                                        TelegramSendMessageSchedule::upsert(
                                                            ['sending_id' => $this->id, 'bot_user_id' => $bot_user->bot_user_id],
                                                            ['sending_id', 'bot_user_id'],
                                                            ['updated_at' => now()]
                                                        );
                                                    }

                                                    Notification::make()
                                                        ->title('Данные успешно сохранены!')
                                                        ->success()
                                                        ->send();
                                                }

                                                return redirect('/admin/bots/' . $this->bot_id . '/sendings');

                                            }),

                                    ]),
                                ])
                        ]),



                    ]),
            ])->statePath('data');
    }


}
