<?php
namespace App\Filament\Resources\Bots\Pages;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
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


    }

    public function getHeading(): string
    {
        return $this->bot_name;
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
                EditAction::make()->url(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/sending-admin")
                    ->visible(fn() => auth()->user()->can('Update:Sending') && auth()->user()->hasRole('super_admin')),
                DeleteAction::make()
                    ->before(function (DeleteAction $action, Sending $record) {
                        $botSendingAdminDeleteRecord = new BotSendingAdminDeleteRecord();
                        $botSendingAdminDeleteRecord->handle($record, $action);
                    })
                    ->visible(fn() => auth()->user()->can('Delete:Sending') && auth()->user()->hasRole('super_admin'))
            ])->recordUrl(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/sending-admin");
    }
}
