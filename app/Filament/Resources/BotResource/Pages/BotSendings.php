<?php

namespace App\Filament\Resources\BotResource\Pages;

use App\Filament\Resources\BotResource;
use App\Models\Core\Bot;
use App\Models\Core\MiniAppBanner;
use App\Models\Core\MiniAppBannerLinkPage;
use App\Models\Core\Sending;
use App\Models\Core\BotMessage;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;


class BotSendings extends Page implements HasTable
{
    use InteractsWithTable;


    protected static string $resource = BotResource::class;

    protected static string $view = 'filament.resources.bot-resource.pages.sendings';


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
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Рассылка')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bot_message.name')
                    ->label('Сообщение')
                    ->searchable(),
                Tables\Columns\TextColumn::make('send_datetime')
                    ->label('Отправка')
                    ->dateTime('d.m.Y H:i:s')
            ])

            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->url(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/sending-admin"),
                Tables\Actions\DeleteAction::make()
                    ->after(function (Sending $record) {
                        return false;
                    })

            ])
            ->recordUrl(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/sending-admin")
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
