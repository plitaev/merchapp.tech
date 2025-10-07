<?php

namespace App\Filament\Resources\Bots\Pages;

use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Bots\BotResource;
use App\Models\Core\Bot;
use App\Models\Core\Sending;
use App\Models\Core\BotMessage;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;


class Sendings extends Page implements HasTable
{
    use InteractsWithTable;


    protected static string $resource = BotResource::class;

    protected string $view = 'filament.resources.bot-resource.pages.sendings';


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
            ->query(Sending::whereHas('bot_message', function ($query) {
                $query->where('bot_id', $this->bot_id);
            }))
            ->columns([
                TextColumn::make('bot_message.name')
                    ->label('Сообщение бота')
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Наименование')
                    ->searchable(),
                TextColumn::make('send_datetime')
                    ->label('Дата отправления рассылки')
                    ->dateTime('d.m.Y H:i:s'),
                TextColumn::make('created_at')
                    ->label('Создан')
                    ->dateTime('d.m.Y H:i:s')
            ])

            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()->url(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/sending-admin"),
                DeleteAction::make(),

            ])
            ->recordUrl(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/sending-admin")
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
