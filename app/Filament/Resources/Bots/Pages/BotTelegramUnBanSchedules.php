<?php

namespace App\Filament\Resources\Bots\Pages;

use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Bots\BotResource;
use App\Models\Core\Bot;
use App\Models\Core\TelegramUnbanSchedule;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;


class BotTelegramUnBanSchedules extends Page implements HasTable
{
    use InteractsWithTable;


    protected static string $resource = BotResource::class;

    protected string $view = 'filament.resources.bot-resource.pages.bot-telegram-unban-schedules';


    public static ?string $label = "Разбаны";
    public static ?string $navigationLabel = "Разбаны";
    public static ?string $title = "";

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
            ->query(
                TelegramUnbanSchedule::where('bot_id', $this->bot_id)
            )
            ->columns([
                TextColumn::make('telegram_chat.first_name')
                    ->label('Имя')
                    ->searchable(),
                TextColumn::make('telegram_chat.last_name')
                    ->label('Фамилия')
                    ->searchable(),
                TextColumn::make('telegram_chat.username')
                    ->label('Ник')
                    ->searchable(),
                TextColumn::make('telegram_chat.email')
                    ->label('Email'),
                TextColumn::make('run_status_name.name')
                    ->label('Статус удаления'),
                TextColumn::make('unban_status_name.name')
                    ->label('Статус разблокировки'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()->url(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/telegram-unban-schedule-admin"),
                DeleteAction::make(),

            ])
            ->recordUrl(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/telegram-unban-schedule-admin")
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
