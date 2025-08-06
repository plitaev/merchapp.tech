<?php

namespace App\Filament\Resources\BotResource\Pages;

use App\Filament\Resources\BotResource;
use App\Models\Core\Bot;
use App\Models\Core\BotMessage;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class BotMessages extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = BotResource::class;

    protected static string $view = 'filament.resources.bot-resource.pages.bot-messages';

    public static ?string $label = "Сообщение";
    public static ?string $navigationLabel = "Сообщения";
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
            ->query(BotMessage::where('bot_id', $this->bot_id))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Название (Только в панели администратора)')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bot_message_type.name')
                    ->label('Тип сообщения'),
                Tables\Columns\TextColumn::make('bot_message_appointment.name')
                    ->label('Назначение'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->url(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/message-admin"),
                Tables\Actions\DeleteAction::make()
            ])
            ->recordUrl(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/message-admin")
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
