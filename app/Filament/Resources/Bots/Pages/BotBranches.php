<?php
namespace App\Filament\Resources\Bots\Pages;
use App\Filament\Resources\Bots\BotResource;

use App\Models\Core\BotBranch;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;

use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\Page;
use Filament\Notifications\Notification;

use App\Actions\Core\BotSendingAdmin\BotSendingAdminDeleteRecord;

use App\Models\Core\Bot;
use App\Models\Core\Sending;


class BotBranches extends Page implements HasTable
{
    use InteractsWithTable;


    protected static string $resource = BotResource::class;

    protected string $view = 'filament.resources.bot-resource.pages.bot-branches';


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
            ->defaultSort('created_at', 'desc')
            ->query(BotBranch::where('bot_id', $this->bot_id))
            ->columns([
                TextColumn::make('name')
                    ->label('Название')
                    ->searchable(),
                TextColumn::make('alias')
                    ->label('Параметр в ссылке')
                    ->searchable()
            ])

            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()->url(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/branch-admin"),
                DeleteAction::make()
                    ->before(function (DeleteAction $action, Sending $record) {
                        $botSendingAdminDeleteRecord = new BotSendingAdminDeleteRecord();
                        $botSendingAdminDeleteRecord->handle($record, $action);
                    })
            ])->recordUrl(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/branch-admin");
    }
}
