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

}
