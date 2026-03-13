<?php

namespace App\Filament\Resources\MiniAppVideos\Pages;

use App\Models\Core\BotAdminLog;
use App\Models\Core\MiniApp;
use App\Models\Core\MiniAppVideoLinkPage;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Actions\ViewAction;
use DB;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use Filament\Schemas\Components\Text;

use App\Models\Core\BotUser;
use App\Models\Core\User;

use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

use App\Filament\Resources\Bots\BotResource;
use App\Models\Core\Bot;
use App\Models\Core\BotUserBanSchedule;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class AdminMiniAppVideoLinkPages extends Page implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;


    protected static string $resource = BotResource::class;

    protected string $view = 'filament.resources.bot-resource.pages.bot-telegram-ban-schedules';


    public static ?string $label = "Баны";
    public static ?string $navigationLabel = "Баны";
    public static ?string $title = "";

    public int $mini_app_page_id;
    public int $mini_app_video_id;

    public string $bot_name;
    public ?array $data_ban_user = [];

    public function mount(int $mini_app_page_id, $mini_app_video_id): void
    {
        $this->mini_app_page_id = $mini_app_page_id;
        $this->mini_app_video_id = $mini_app_video_id;

        $this->form_ban_user->fill([]);

        if (!Auth::user()->hasPermissionTo('View:BotUserBanSchedule')) {
            redirect('/admin/bots/access');
        }

    }

    protected function getForms(): array
    {
        return ['form_ban_user'];
    }

    public function getHeading(): string
    {
        return '';
    }

    public function getTitle(): string
    {
        return $this->bot_name;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                MiniAppVideoLinkPage::with('miniapp_page')->where('mini_app_video_id', $this->mini_app_video_id)
            )
            ->persistSearchInSession()
            ->columns([
                TextColumn::make('miniapp_page.name')
                    ->label('Страница')
                    ->searchable()
            ])
            ->filters([
                //
            ])
            ->recordActions([
                DeleteAction::make()
                    ->visible(auth()->user()->can('Delete:BotUserBanSchedule')),

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(auth()->user()->can('Delete:BotUserBanSchedule')),

                ]),
            ]);
    }

    public function form_ban_user(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Пользователи')
                    ->description('')
                    ->schema([
                    ]),
                Actions::make([
                    Action::make('Сохранить')
                        ->action(function () {
                            $formdata = $this->form_ban_user->getState();

                            Notification::make()
                                ->title('Данные успешно сохранены!')
                                ->success()
                                ->send();

                            $this->dispatch('close-modal', id: 'add-page-modal');
                        })
                        ->visible(auth()->user()->hasPermissionTo('Create:BotUserBanSchedule')),
                    Action::make('Отмена')
                        ->action(function () {
                            $this->dispatch('close-modal', id: 'add-page-modal');
                        })
                ])
            ])->statePath('data_ban_user');
    }
}
