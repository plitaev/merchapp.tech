<?php
namespace App\Filament\Resources\Bots\Pages;

use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Bots\BotResource;
use Filament\Actions\ViewAction;
use App\Models\Core\Bot;
use App\Models\Core\TelegramSupergroup;
use App\Models\Core\User;

use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class BotSupergroups extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = BotResource::class;

    protected string $view = 'filament.resources.bot-resource.pages.bot-supergroups';

    public static ?string $label = "";
    public static ?string $navigationLabel = "Супергруппы";
    public static ?string $title = "Супергруппы";

    public int $bot_id;
    public string $bot_name;

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
            ->query(TelegramSupergroup::where('bot_id', $this->bot_id))
            ->persistSearchInSession()
            ->columns([
                TextColumn::make('name')
                    ->label('Название (Только в панели администратора)')
                    ->searchable(),
                TextColumn::make('telegram_id')
                    ->label('ID в Telegram'),
                TextColumn::make('give_access_name.name')
                    ->label('Выдавать доступ'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()->url(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/supergroup-admin")
                    ->visible(!auth()->user()->can('Create:TelegramSupergroup')),
                EditAction::make()->url(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/supergroup-admin")
                    ->visible(auth()->user()->can('Update:TelegramSupergroup')),

                DeleteAction::make()
                    ->visible(auth()->user()->can('Delete:TelegramSupergroup')),


            ])
            ->recordUrl(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/supergroup-admin")
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

}
