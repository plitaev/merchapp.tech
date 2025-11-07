<?php
namespace App\Filament\Resources\Bots\Pages;

use App\Models\Core\BotUser;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Bots\BotResource;
use App\Models\Core\Bot;
use App\Models\Core\PayGuest;
use App\Models\Core\User;

use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Support\Enums\IconPosition;

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
            ->defaultSort('created_at', 'desc')
            ->persistSearchInSession()
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
                Tables\Columns\TextColumn::make('email')
                    ->icon('heroicon-m-clipboard')
                    ->iconPosition(IconPosition::Before)
                    ->iconColor('gray')
                    ->extraAttributes(fn (PayGuest $record) => [
                        'x-data' => '{}',
                        'x-on:click.prevent' => "
                        if (\$event.target.closest('svg')) {
                         navigator.clipboard.writeText('{$record->email}');
                         \$tooltip('Скопировано', { timeout: 1500 });
                        } else {
                         window.location.href = '/admin/bots/".$this->bot_id."/".$record->id."/pay-guest-admin';
                        }"
                    ])
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Продукт')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Стоимость')
                    ->searchable(),
                Tables\Columns\TextColumn::make('days')
                    ->label('Дни')
                    ->searchable()
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make()->url(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/pay-guest-admin")
                    ->visible(fn() => auth()->user()->can('Update:PayGuests')),
                DeleteAction::make()
                    ->visible(fn() => auth()->user()->can('Delete:PayGuests')),
            ])
            ->recordUrl(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/pay-guest-admin")
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

}
