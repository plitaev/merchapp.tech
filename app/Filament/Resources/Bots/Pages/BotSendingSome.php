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


class BotSendingSome extends Page implements HasForms, HasTable, HasInfolists
{
    use InteractsWithForms;
    use InteractsWithInfolists;
    use InteractsWithTable;

    protected static string $resource = BotResource::class;

    protected string $view = 'filament.resources.bot-resource.pages.bot-sending-some';

    public int $id;

    public int $bot_id;
    public int $bot_message_id;
    public string $bot_name;
    public int $sent_users;
    public int $new_bot_id;

    protected static ?string $model = Sending::class;

    public static ?string $label = "Рассылка";
    public static ?string $navigationLabel = "Рассылка";
    public static ?string $title = "Рассылка";
    public ?array $data = [];

    public function getRecord(): ?Model
    {
        return Sending::class;
    }

    public function getHeading(): string
    {
        if ($this->id > 0) {
            return "Загрузить контакты";
        } else {
            return "Загрузить контакты";
        }
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
            $data = ($id>0?Sending::with('bot_message')->find($id)->toArray():["bot_id" => $bot_id]);
            $this->sent_users = TelegramSendMessageSchedule::where('run_status', '>', 0)->where('sending_id', $id)->count();
        } else {
            $data = [];
            $data['bot_id'] = $bot_id;
            $this->sent_users = 0;
        }

        $bot = Bot::select('name')->find($bot_id);
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
                Section::make('Получатели рассылки')
                    ->description('')
                    ->schema([
                        Hidden::make('sending_id'),
                        Section::make('Значения для загрузки контактов')
                            ->description('Строка, cо значениями через запятую')
                            ->schema([
                                Textarea::make('email_string')
                                    ->label('Email'),
                                Textarea::make('username_string')
                                    ->label('Username'),
                            ]),
                    ]),
                Actions::make([
                    Action::make('Сохранить')
                        ->action(function () {
                            $data = $this->form->getState();

                            $bot_user_id = '';
                            $splits = array(',',', ',' ','\n',' \n', '\n ');

                            $email_str  = $data['email_string'];
                            $email_mass = explode('\n', $email_str);
                            if($email_mass) {
                                foreach ($email_mass as $email) {
                                    $bot_user_id = BotUser::where('email', $email)->first();

                                    if ($bot_user_id) {
                                        $count = TelegramSendMessageSchedule::where('bot_user_id', $bot_user_id->id)
                                            ->where('sending_id', $this->id)
                                            ->count();
                                        if ($count == 0) {
                                            TelegramSendMessageSchedule::upsert(
                                                ['sending_id' => $this->id, 'bot_user_id' => $bot_user_id->id],
                                                ['sending_id', 'bot_user_id'],
                                                ['updated_at' => now()]
                                            );
                                        }
                                    }
                                }
                            }

                            $username_str  = $data['username_string'];
                            $username_mass = explode('\n', $username_str);

                            if($username_mass) {
                                foreach ($username_mass as $username) {
                                    $bot_user_id = BotUser::where('username', $username)->first();
                                    if ($bot_user_id) {
                                        $count = TelegramSendMessageSchedule::where('bot_user_id', $bot_user_id->id)
                                            ->where('sending_id', $this->id)
                                            ->count();
                                        if ($count == 0) {
                                            TelegramSendMessageSchedule::upsert(
                                                ['sending_id' => $this->id, 'bot_user_id' => $bot_user_id->id],
                                                ['sending_id', 'bot_user_id'],
                                                ['updated_at' => now()]
                                            );
                                        }
                                    }
                                }
                            }

                            Notification::make()
                                ->title('Данные успешно загружены!')
                                ->success()
                                ->send();

                            return redirect('/admin/bots/'.$this->bot_id.'/'.$this->id.'/sending-admin');
                        }),
                    Action::make('Отмена')
                        ->action(function () {
                            $this->dispatch('close-modal', id: 'add-page-modal');
                        })
                ])
            ])->statePath('data');
    }

}
