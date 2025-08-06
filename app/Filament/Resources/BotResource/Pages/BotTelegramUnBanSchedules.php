<?php

namespace App\Filament\Resources\BotResource\Pages;

use App\Filament\Resources\BotResource;
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

    protected static string $view = 'filament.resources.bot-resource.pages.bot-telegram-unban-schedules';


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
                Tables\Columns\TextColumn::make('telegram_chat.first_name')
                    ->label('Имя')
                    ->searchable(),
                Tables\Columns\TextColumn::make('telegram_chat.last_name')
                    ->label('Фамилия')
                    ->searchable(),
                Tables\Columns\TextColumn::make('telegram_chat.username')
                    ->label('Ник')
                    ->searchable(),
                Tables\Columns\TextColumn::make('telegram_chat.email')
                    ->label('Email'),
                Tables\Columns\TextColumn::make('run_status_name.name')
                    ->label('Статус удаления'),
                Tables\Columns\TextColumn::make('unban_status_name.name')
                    ->label('Статус разблокировки'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->url(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/telegram-unban-schedule-admin"),
                Tables\Actions\DeleteAction::make(),

            ])
            ->recordUrl(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/telegram-unban-schedule-admin")
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
