<?php
namespace App\Filament\Resources\Bots\Pages;

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
use App\Models\Core\BotMessages;
use App\Models\Core\TelegramSendMessageSchedules;
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

class BotUserLinkMiniAppVideoAdmin extends Page implements HasForms, HasInfolists
{
    use InteractsWithInfolists;
    use InteractsWithForms;

    protected static string $resource = BotResource::class;

    protected string $view = 'filament.resources.bot-resource.pages.bot-user-link-mini-app-video-admin';

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


    public function getTitle(): string
    {
        return "Видео пользователя";
    }


    public function mount(int $bot_id, int $bot_user_id, int $id): void
    {
        //if (!Auth::user()->hasPermissionTo('View:Pay')) redirect('/admin/bots/access');

        $this->id = $id;

        $bot = Bot::select('name')->find($bot_id);
        $this->bot_id = $bot_id;
        $this->bot_name = $bot->name;

        $this->bot_user_id = $bot_user_id;

        if ($id > 0) {
            $botUserLinkMiniAppVideo = BotUserLinkMiniAppVideo::find($id);
            $mini_app_video_id = BotUserLinkMiniAppVideo::select('mini_app_video_id')->where('bot_user_id', $bot_user_id)->pluck('mini_app_video_id')->toArray();
            unset($mini_app_video_id[array_search($botUserLinkMiniAppVideo->mini_app_video_id, $mini_app_video_id)]);

            $data = $botUserLinkMiniAppVideo->toArray();
        } else {
            $mini_app_video_id = BotUserLinkMiniAppVideo::select('mini_app_video_id')->where('bot_user_id', $bot_user_id)->pluck('mini_app_video_id')->toArray();
            $data = [];
        }

        $this->mini_app_video_id = $mini_app_video_id;
        $this->form->fill($data);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Видео пользователя')
                    ->description('Укажите видео, прикрепленные к пользователю')
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

                        Forms\Components\Select::make('mini_app_video_id')
                            ->label('Видео')
                            ->options(MiniAppVideo::pluck('name','id'))
                            ->searchable()
                            ->disabled(auth()->user()->can('Update:Pay')?false:true),

                    ]),

                Actions::make([
                    Action::make('Сохранить')
                        ->action(function () {
                            $data = $this->form->getState();

                            $data['bot_user_id'] = $this->bot_user_id;

                            if ($this->id > 0) {
                                $data['id'] = $this->id;
                                BotUserLinkMiniAppVideo::where('id', $this->id)->update($data);
                            } else {
                                BotUserLinkMiniAppVideo::create($data);
                            }

                            Notification::make()
                                ->title('Данные успешно сохранены!')
                                ->success()
                                ->send();

                            return redirect('/admin/bots/'.$this->bot_id.'/'.$this->bot_user_id.'/bot-user-link-mini-app-videos');

                        })
                        ->disabled(auth()->user()->can('Create:Pay')?false:true),
                    Action::make('Cancel')
                        ->action(function () {
                            return redirect('/admin/bots/'.$this->bot_id.'/'.$this->bot_user_id.'/bot-user-link-mini-app-videos');
                        })
                        ->label('Отменить и вернуться назад')
                ]),

            ])->statePath('data');

    }

}
