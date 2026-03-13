<?php
namespace App\Filament\Resources\MiniAppVideos\Pages;

use App\Models\Core\MiniAppVideo;
use App\Models\Core\MiniAppVideoLinkPage;
use App\Models\Core\TelegramSendMessageLog;
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

use App\Actions\Core\Sending\SendingSave;

use App\Models\Core\Bot;
use App\Models\Core\Sending;
use App\Models\Core\SendingAppointment;
use App\Models\Core\SendingButton;
use App\Models\Core\SendingListener;
use App\Models\Core\SendingType;
use App\Models\Core\BotUser;
use App\Models\Core\Funnel;
use App\Models\Core\FunnelCondition;
use App\Models\Core\FunnelConditionTrigger;
use App\Models\Core\Listener;
use Illuminate\Support\Facades\Auth;

class AdminMiniAppVideoLinkPages extends Page implements HasTable, HasInfolists
{
    use InteractsWithInfolists;
    use InteractsWithTable;

    protected static string $resource = MiniAppVideo::class;

    protected string $view = 'filament.resources.bot-resource.pages.bot-telegram-send-message-logs';

    public int $mini_app_page_id;
    public int $mini_app_video_id;

    protected static ?string $model = MiniAppVideoLinkPage::class;

    public static ?string $label = "Страницы с этим видео";
    public static ?string $navigationLabel = "Страницы с этим видео";
    public static ?string $title = "Страницы с этим видео";
    public ?array $data = [];

    public function getRecord(): ?Model
    {
        return MiniAppVideoLinkPage::class;
    }

    public function getHeading(): string
    {

        return "Страницы с этим видео";

    }

    protected function getHeaderActions(): array
    {
        return [];
    }


    public function mount(int $mini_app_page_id, int $mini_app_video_id): void
    {
        $this->mini_app_page_id = $mini_app_page_id;
        $this->mini_app_video_id = $mini_app_video_id;
    }

    public function getTitle(): string
    {
        return "Сообщения пользователя";
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(MiniAppVideoLinkPage::with('miniapp_page')->where('video_id', $this->mini_app_video_id))
            ->persistSearchInSession()
            ->columns([
                TextColumn::make('miniapp_page.name')
                    ->label('Название')
            ])
            ->filters([]);
    }

}
