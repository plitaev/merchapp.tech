<?php
namespace App\Filament\Resources\Bots\Pages;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\DeleteAction;
use App\Models\Core\BotMessages;
use App\Models\Core\TelegramSendMessageSchedules;
use Illuminate\Support\HtmlString;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

use App\Filament\Resources\Bots\BotResource;

use Filament\Forms;
use Filament\Forms\Components;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Textarea;
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

use App\Actions\Core\BotUserLinkMiniAppVideo\BotUserLinkMiniAppVideoSave;

use App\Models\Core\Bot;
use App\Models\Core\BotUserLinkMiniAppVideo;
use App\Models\Core\MiniAppVideo;
use App\Models\Core\BotUserLinkMiniAppVideoAppointment;
use App\Models\Core\BotUserLinkMiniAppVideoButton;
use App\Models\Core\BotUserLinkMiniAppVideoListener;
use App\Models\Core\BotUserLinkMiniAppVideoType;
use App\Models\Core\BotUser;
use App\Models\Core\Funnel;
use App\Models\Core\FunnelCondition;
use App\Models\Core\FunnelConditionTrigger;
use App\Models\Core\Listener;
use Illuminate\Support\Facades\Auth;

class BotUserLinkMiniAppVideos extends Page implements HasTable, HasInfolists
{
    use InteractsWithInfolists;
    use InteractsWithTable;

    protected static string $resource = BotResource::class;

    protected string $view = 'filament.resources.bot-resource.pages.bot-user-link-mini-app-videos';

    public int $id;

    public int $bot_id;
    public int $bot_message_id;

    public int $b_user_id;
    public int $bot_user_id;
    public int $bot_user;

    public string $bot_name;


    protected static ?string $model = BotUserLinkMiniAppVideo::class;

    public static ?string $label = "Видео";
    public static ?string $navigationLabel = "Видео";
    public static ?string $title = "Видео";
    public ?array $data = [];

    public function getRecord(): ?Model
    {
        return BotUserLinkMiniAppVideo::class;
    }

    public function getHeading(): string
    {

        return $this->bot_name;

    }

    protected function getHeaderActions(): array
    {
        return [];
    }


    public function mount(int $bot_id, int $bot_user_id): void
    {
        $bot = Bot::select('name')->find($bot_id);
        $this->bot_name = $bot->name;

        $this->bot_id = $bot_id;
        $this->bot_user_id = $bot_user_id;
//        if (!Auth::user()->hasPermissionTo('View:BotUserLinkMiniAppVideo')) {
//            redirect('/access');
//        }
    }

    public function getTitle(): string
    {
        return "Видео пользователя";
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('updated_at', 'desc')
            ->query(
                BotUserLinkMiniAppVideo::with('miniapp_video')
                    ->where('bot_user_id', $this->bot_user_id)

            )
            ->persistSearchInSession()
            ->columns([
                TextColumn::make('miniapp_video.name')
                    ->label('Наименование')
                    ->searchable()
            ])
            ->recordActions([
            DeleteAction::make()
                ->visible(auth()->user()->can('Delete:Pay'))
            ])->filters([
                //
            ]);
    }

}
