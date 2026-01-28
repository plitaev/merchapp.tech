<?php
namespace App\Filament\Resources\Bots\Pages;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;

use App\Filament\Resources\Bots\BotResource;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Notifications\Notification;
use Filament\Tables\Table;

use App\Actions\Core\BotSendingAdmin\BotSendingAdminDeleteRecord;

use App\Models\Core\Bot;
use App\Models\Core\Sending;
use App\Models\Core\TelegramSendMessageSchedule;
use App\Models\Core\User;
use Illuminate\Support\Facades\Auth;


class BotSendings extends Page implements HasTable
{
    use InteractsWithTable;


    protected static string $resource = BotResource::class;

    protected string $view = 'filament.resources.bot-resource.pages.bot-sendings';


    public static ?string $label = "Рассылки";
    public static ?string $navigationLabel = "Рассылки";
    public static ?string $title = "";

    public int $bot_id;
    public string $bot_name;

    public string $name;

    public function mount(int $bot_id): void
    {
        $this->bot_id = $bot_id;
        $bot = Bot::select('name')->find($bot_id);

        $this->bot_name = $bot->name;

        if (!Auth::user()->hasPermissionTo('View:Sending')) {
            redirect('/admin/bots/access');
        }
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
                Sending::whereHas('bot_message', function ($query) {
                    $query->where('bot_id', $this->bot_id);
                })
                    ->orderByDesc('send_datetime')
            )
            ->persistSearchInSession()
            ->columns([
                TextColumn::make('name')
                    ->label('Рассылка')
                    ->searchable(),
                TextColumn::make('bot_message.name')
                    ->label('Сообщение')
                    ->searchable(),
                TextColumn::make('send_datetime')
                    ->label('Отправка')
                    ->dateTime('d.m.Y H:i:s')
            ])

            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()->url(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/sending-admin")
                    ->visible(!auth()->user()->hasPermissionTo('Create:Sending')),
                EditAction::make()->url(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/sending-admin")
                    ->visible(auth()->user()->hasPermissionTo('Update:Sending')),
                DeleteAction::make()
                    ->before(function (DeleteAction $action, Sending $record) {
                        $botSendingAdminDeleteRecord = new BotSendingAdminDeleteRecord();
                        $botSendingAdminDeleteRecord->handle($record, $action);
                    })
                    ->visible(auth()->user()->hasPermissionTo('Delete:Sending'))
            ])->recordUrl(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/sending-admin");
    }
}
