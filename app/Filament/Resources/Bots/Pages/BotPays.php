<?php

namespace App\Filament\Resources\Bots\Pages;

use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Actions\Core\DateEnd\DateEndCacheForPay;
use App\Filament\Resources\Bots\BotResource;
use App\Models\Core\Bot;
use App\Models\Core\Pay;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class BotPays extends Page implements HasTable
{
    use InteractsWithTable;


    protected static string $resource = BotResource::class;

    protected string $view = 'filament.resources.bot-resource.pages.bot-pays';


    public static ?string $label = "Платежи";
    public static ?string $navigationLabel = "Платежи";
    public static ?string $title = "";

    public int $bot_id;
    public string $bot_name;

    public int $pay_bot_id;
    public int $pay_bot_user_id;

    public array $pay_bulk_delete_ids;

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
                Pay::with('bot_user:id,first_name,last_name,username,email')
                    ->with('product:id,name')
                    ->with('product_type:product_types.id,product_types.name')
                    ->with('bot')
                    ->with('recurrent_name:id,name')
                    ->with('recurrent_status_name:id,name')
                    ->select('id', 'bot_user_id', 'product_id', 'price', 'days', 'recurrent', 'recurrent_status')
                    ->whereHas('bot', function ($query) {
                        $query->where('bot_id', $this->bot_id);
                    })
                    ->where('status', 1)
                    ->orderByDesc('updated_at')
            )
            ->columns([

                TextColumn::make('bot_user.first_name')
                    ->label('Имя')
                    ->searchable(),
                TextColumn::make('bot_user.last_name')
                    ->label('Фамилия')
                    ->searchable(),
                TextColumn::make('bot_user.email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('bot_user.username')
                    ->label('Ник')
                    ->searchable(),
                TextColumn::make('product.name')
                    ->label('Тариф')
                    ->searchable(),
                TextColumn::make('price')
                    ->label('Стоимость'),
                TextColumn::make('days')
                    ->label('Дни'),
                TextColumn::make('product_type.name')
                    ->label('Способ оплаты'),
                TextColumn::make('recurrent_name.name')
                    ->label('Рекуррент'),
                TextColumn::make('recurrent_status_name.name')
                    ->label('Статус рекуррента'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()->url(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/pay-admin"),
                DeleteAction::make()
                    ->before(function ($record) {
                        $pay = Pay::with('bot')->find($record->id);

                        $this->pay_bot_id = $pay->bot->id;

                        $bot_user_id = ($pay->gift_bot_user_id ?? $pay->bot_user_id);
                        $this->pay_bot_user_id = $bot_user_id;
                    })
                    ->after(function ($record) {
                        $dateEndCacheForPay = new DateEndCacheForPay();
                        $date_end_cache = $dateEndCacheForPay->handle($this->pay_bot_user_id);

                        Notification::make()
                            ->title("Установлена дата окончания участия: " . $date_end_cache)
                            ->success()
                            ->send();
                    }),

            ])
            ->recordUrl(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/pay-admin")
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->before(function ($records) {
                            foreach ($records as $record) {
                                $pay = Pay::with('bot')->find($record->id);

                                $bot_user_id = ($pay->gift_bot_user_id ?? $pay->bot_user_id);

                                $this->pay_bulk_delete_ids[] = ["bot_user_id" => $bot_user_id];
                            }
                        })
                        ->after(function ($records) {
                            foreach ($this->pay_bulk_delete_ids as $bulk) {
                                $dateEndCacheForPay = new DateEndCacheForPay();
                                $dateEndCacheForPay->handle($bulk["bot_user_id"]);
                            }
                        })
                ]),
            ]);
    }
}
