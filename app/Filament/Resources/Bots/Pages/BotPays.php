<?php

namespace App\Filament\Resources\Bots\Pages;

use App\Filament\Resources\Bots\BotResource;

use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\ViewAction;

use Filament\Support\Enums\IconPosition;
use Filament\Support\Enums\Width;

use App\Actions\Core\BotSendMessage\BotSendMessage;
use App\Actions\Core\BotUser\BotUserBanByDeletePay;
use App\Actions\Core\DateEnd\DateEndCacheForPay;
use App\Actions\Core\Pay\PayRefund;

use App\Actions\Project\ClubAccess\BotCabinetRecurrentCheck;

use App\Models\Core\Bot;
use App\Models\Core\BotUser;
use App\Models\Core\Pay;
use App\Models\Core\User;
use Illuminate\Support\Facades\Auth;

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

    public function getMaxContentWidth(): Width{return Width::ScreenTwoExtraLarge;}

    public function mount(int $bot_id): void
    {
        $this->bot_id = $bot_id;
        $bot = Bot::select('name')->find($bot_id);

        $this->bot_name = $bot->name;

        if (!Auth::user()->hasPermissionTo('View:Pay')) {
            redirect('/admin/bots/access');
        }
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
            ->query(
                Pay::with('bot_user:id,first_name,last_name,username,email')
                    ->with('pay_system')
                    ->with('product:id,name')
                    ->with('product_type:product_types.id,product_types.name')
                    ->with('bot')
                    ->with('recurrent_name:id,name')
                    ->with('recurrent_status_name:id,name')
                    ->select('id', 'bot_user_id', 'product_id', 'price', 'days', 'recurrent', 'recurrent_status', 'created_at')
                    ->whereHas('bot', function ($query) {
                        $query->where('bot_id', $this->bot_id);
                    })
                    ->where('status', 1)
            )
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->date('d.m.Y H:i:s')
                    ->sortable()
                    ->searchable()
                    ->label('Дата'),
                Tables\Columns\TextColumn::make('bot_user.email')
                    ->icon('heroicon-m-clipboard')
                    ->iconPosition(IconPosition::Before)
                    ->iconColor('gray')
                    ->extraAttributes(fn (Pay $record) => [
                        'x-data' => '{}',
                        'x-on:click.prevent' => "
                        if (\$event.target.closest('svg')) {
                         navigator.clipboard.writeText('{$record->bot_user->email}');
                         \$tooltip('Скопировано', { timeout: 1500 });
                        } else {
                         window.location.href = '/admin/bots/".$this->bot_id."/".$record->id."/pay-admin';
                        }"
                    ])
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bot_user.username')
                    ->icon('heroicon-m-clipboard')
                    ->iconPosition(IconPosition::Before)
                    ->iconColor('gray')
                    ->extraAttributes(fn (Pay $record) => [
                        'x-data' => '{}',
                        'x-on:click.prevent' => "
                        if (\$event.target.closest('svg')) {
                         navigator.clipboard.writeText('{$record->bot_user->username}');
                         \$tooltip('Скопировано', { timeout: 1500 });
                        } else {
                         window.location.href = '/admin/bots/".$this->bot_id."/".$record->id."/pay-admin';
                        }"
                    ])
                    ->label('Ник')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bot_user.first_name')
                    ->icon('heroicon-m-clipboard')
                    ->iconPosition(IconPosition::Before)
                    ->iconColor('gray')
                    ->extraAttributes(fn (Pay $record) => [
                        'x-data' => '{}',
                        'x-on:click.prevent' => "
                        if (\$event.target.closest('svg')) {
                         navigator.clipboard.writeText('{$record->bot_user->first_name}');
                         \$tooltip('Скопировано', { timeout: 1500 });
                        } else {
                         window.location.href = '/admin/bots/".$this->bot_id."/".$record->id."/pay-admin';
                        }"
                    ])
                    ->label('Имя')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bot_user.last_name')
                    ->icon('heroicon-m-clipboard')
                    ->iconPosition(IconPosition::Before)
                    ->iconColor('gray')
                    ->extraAttributes(fn (Pay $record) => [
                        'x-data' => '{}',
                        'x-on:click.prevent' => "
                        if (\$event.target.closest('svg')) {
                         navigator.clipboard.writeText('{$record->bot_user->last_name}');
                         \$tooltip('Скопировано', { timeout: 1500 });
                        } else {
                         window.location.href = '/admin/bots/".$this->bot_id."/".$record->id."/pay-admin';
                        }"
                    ])
                    ->label('Фамилия')
                    ->searchable(),
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Тариф')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Стоимость'),
                Tables\Columns\TextColumn::make('days')
                    ->label('Дни'),
                Tables\Columns\TextColumn::make('pay_system.name')
                    ->label('ПС'),
                Tables\Columns\TextColumn::make('product_type.name')
                    ->label('Способ оплаты'),
                Tables\Columns\IconColumn::make('recurrent')
                    ->boolean()
                    ->label('Рекуррентный тип платежа')
                    ->alignCenter()
                    ->trueColor('info')
                    ->falseColor('warning'),
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make()->url(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/message-admin")
                    ->visible(!auth()->user()->can('Create:Pay')),
                Action::make('refund')
                    ->label('Вернуть')
                    ->color('info')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->requiresConfirmation()
                    ->action(function (Pay $record) {
                        $payRefund = new PayRefund();
                        $payRefund->handle($record->id);
                    })
                    ->visible(auth()->user()->can('Update:Pay')),
                EditAction::make()->url(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/pay-admin")
                    ->visible(auth()->user()->can('Update:BotMessage')),
                DeleteAction::make()
                    ->before(function ($record) {
                        $pay = Pay::with('bot')->find($record->id);

                        $this->pay_bot_id = $pay->bot->id;

                        $bot_user_id = ($pay->gift_bot_user_id ?? $pay->bot_user_id);
                        $this->pay_bot_user_id = $bot_user_id;
                    })
                    ->after(function ($record) {
                        $botSendMessage = new BotSendMessage();
                        $dateEndCacheForPay = new DateEndCacheForPay();
                        $date_end = $dateEndCacheForPay->handle($this->pay_bot_user_id);

                        Notification::make()
                            ->title("Установлена дата окончания участия: " . $date_end)
                            ->success()
                            ->send();

                        $bot_user = BotUser::with('bot')->find($this->pay_bot_user_id);

                        $botUserBanByDeletePay = new BotUserBanByDeletePay();
                        $botUserBanByDeletePay->handle($bot_user);

                        $botCabinetRecurrentCheck = new BotCabinetRecurrentCheck();
                        $botCabinetRecurrentCheck->handle($bot_user);
                    })
                    ->visible(auth()->user()->can('Delete:Pay')),
            ])
            ->recordUrl(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/pay-admin")
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(auth()->user()->can('Delete:Pay'))
                    ->before(function ($records) {
                            foreach ($records as $record) {
                                $pay = Pay::with('bot')->find($record->id);
                                $bot_user_id = ($pay->gift_bot_user_id ?? $pay->bot_user_id);
                                $this->pay_bulk_delete_ids[] = ["bot_user_id" => $bot_user_id];
                            }
                        })
                        ->after(function ($records) {
                            $botCabinetRecurrentCheck = new BotCabinetRecurrentCheck();
                            $botSendMessage = new BotSendMessage();
                            $botUserBanByDeletePay = new BotUserBanByDeletePay();

                            foreach ($this->pay_bulk_delete_ids as $bulk) {
                                $dateEndCacheForPay = new DateEndCacheForPay();
                                $dateEndCacheForPay->handle($bulk["bot_user_id"]);
                            }

                            $bot_users = BotUser::with('bot')->whereIn('id', $this->pay_bulk_delete_ids)->get();
                            foreach ($bot_users as $bot_user) {
                                $botUserBanByDeletePay->handle($bot_user);
                                $botCabinetRecurrentCheck->handle($bot_user);
                            }

                        }),
                ]),
            ]);
    }
}
