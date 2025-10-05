<?php
namespace App\Filament\Resources\BotResource\Pages;

use App\Filament\Resources\BotResource;
use App\Models\Core\Bot;
use App\Models\Core\PayGuest;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class BotPayGuests extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = BotResource::class;

    protected static string $view = 'filament.resources.bot-resource.pages.bot-pay-guests';

    public static ?string $label = "";
    public static ?string $navigationLabel = "Платежи в ожидании";
    public static ?string $title = "Платежи в ожидании";

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
            ->defaultSort('created_at', 'desc')
            ->query(PayGuest::with('bot')
            ->with('product')
            ->where('status', 0)
            ->whereHas('bot', function ($query) {
                $query->where('bot_id', $this->bot_id);
            }))
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->date('d.m.Y H:i:s')
                    ->label('Дата')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Продукт')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Стоимость')
                    ->searchable(),
                Tables\Columns\TextColumn::make('days')
                    ->label('Дни')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gift_name.name')
                    ->label('Подарок')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->url(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/pay-guest-admin"),
                Tables\Actions\DeleteAction::make(),
            ])
            ->recordUrl(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/pay-guest-admin")
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
        }

}
