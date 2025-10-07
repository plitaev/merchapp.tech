<?php
namespace App\Filament\Resources\Bots\Pages;

use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Bots\BotResource;
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

    protected string $view = 'filament.resources.bot-resource.pages.bot-pay-guests';

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
            ->query(PayGuest::with('bot')
            ->with('product')
            ->whereHas('bot', function ($query) {
                $query->where('bot_id', $this->bot_id);
            }))
            ->columns([
                TextColumn::make('product.name')
                    ->label('Продукт')
                    ->searchable(),
                TextColumn::make('price')
                    ->label('Стоимость')
                    ->searchable(),
                TextColumn::make('days')
                    ->label('Дни')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('gift_name.name')
                    ->label('Подарок')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()->url(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/pay-guest-admin"),
                DeleteAction::make(),
            ])
            ->recordUrl(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/pay-guest-admin")
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
        }

}
