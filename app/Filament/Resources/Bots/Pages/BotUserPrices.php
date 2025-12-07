<?php
namespace App\Filament\Resources\Bots\Pages;

use App\Filament\Resources\Bots\BotResource;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

use Filament\Schemas\Schema;

use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Section;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;

use Filament\Infolists\Contracts\HasInfolists;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Concerns\InteractsWithForm;
use Filament\Forms\Contracts\HasForms;

use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;

use App\Models\Core\Bot;
use App\Models\Core\BotUser;
use App\Models\Core\BotUserPrice;
use App\Models\Core\Product;

class BotUserPrices extends Page implements HasTable, HasForms, HasInfolists
{
    use InteractsWithForms;
    use InteractsWithTable;
    use InteractsWithInfolists;


    protected static string $resource = BotResource::class;

    protected string $view = 'filament.resources.bot-resource.pages.bot-user-prices';

    public int $id;

    public int $bot_id;
    public int $bot_message_id;

    public int $bot_user_id;
    public int $bot_user;

    public string $bot_name;
    public string $bot_user_name;


    protected static ?string $model = BotUserPrice::class;

    public static ?string $label = "Индивидуальная цена";
    public static ?string $navigationLabel = "Индивидуальная цена";
    public static ?string $title = "Индивидуальная цена";
    public ?array $data = [];

    public function getRecord(): ?Model
    {
        return BotUserPrice::class;
    }

    protected function getForms(): array
    {
        return ['form'];
    }

    public function getHeading(): string
    {

        return $this->bot_user_name;

    }

    protected function getHeaderActions(): array
    {
        return [];
    }


    public function mount(int $bot_id, int $bot_user_id): void
    {
        $this->id = $bot_user_id;

        $this->bot_id = $bot_id;
        $bot = Bot::select('name')->find($bot_id);
        $this->bot_name = $bot->name;

        $bot_user = BotUser::select('first_name', 'last_name', 'email')->find($bot_user_id);

        $bot_user_name = '';
        if ($bot_user->first_name) $bot_user_name = $bot_user->first_name;
        if ($bot_user->last_name) $bot_user_name .= ' '.$bot_user->last_name;
        if ($bot_user->email) $bot_user_name .= " (".$bot_user->email.")";

        $this->bot_user_name = $bot_user_name;
    }

    public function getTitle(): string
    {
        return $this->bot_name;
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('updated_at', 'desc')
            ->query(
                BotUserPrice::query()->with('product')->where('bot_user_id', $this->bot_user_id)
            )
            ->columns([
                TextColumn::make('product.name')
                    ->label('Продукт'),
                TextColumn::make('price')
                    ->label('Цена'),
            ])
            ->recordActions([
                EditAction::make()->url(fn($record) => "/admin/bots/".$this->bot_id."/".$this->bot_user_id."/".$record->id."/user-price")
                    ->visible(auth()->user()->can('Update:Pay')),
                DeleteAction::make()
                    ->visible(auth()->user()->can('Delete:Pay'))
            ])->filters([])
            ->recordUrl(fn($record) => "/admin/bots/".$this->bot_id."/".$this->bot_user_id."/".$record->id."/user-price");
    }
}
