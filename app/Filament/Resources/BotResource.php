<?php
namespace App\Filament\Resources;

use App\Filament\Resources\BotResource\Pages;
use App\Filament\Resources\BotResource\RelationManagers;
use App\Models\Core\Bot;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Action as FRActon;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;

class BotResource extends Resource
{
    protected static ?string $model = Bot::class;
    public static ?string $label = "Бот";
    public static ?string $navigationLabel = "Боты";
    public static ?string $title = "Боты";

    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-oval-left';

    public static ?int $navigationSort = 2;

    public static function getPluralLabel(): ?string {return "Боты";}

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Название (Только в панели администратора)')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('alias')
                    ->label('Username в Telegram')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('telegram_token')
                    ->label('Telegram-токен (из BotFather)')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('telegram_webhook')
                    ->label('Адрес вебхука Telegram')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Название (Только в панели администратора)')
                    ->searchable(),
                Tables\Columns\TextColumn::make('alias')
                    ->label('Username в Telegram')
                    ->searchable(),
                Tables\Columns\TextColumn::make('telegram_token')
                    ->label('Telegram-токен')
                    ->searchable(),
                Tables\Columns\IconColumn::make('telegram_webhook')
                    ->label('Статус')
                    ->extraAttributes(['class' => 'flex justify-center'])
                    ->options(['heroicon-o-cog-8-tooth'])
                    ->action(
                        Tables\Actions\Action
                            ::make('telegram_webhook')
                            ->action(fn (Bot $record) => $record->advance())
                            ->modalContent(fn (Bot $record): View => view(
                                'core.filament.bot.telegram-webhook-set-second',
                                ['record' => $record]
                            ))
                            ->modalSubmitAction(false)
                            ->modalCancelAction(false)
                    ),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Создано')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Изменено')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            //'index' => Pages\ListBots::route('/'),
            'index' => Pages\AdvancedListBot::route('/'),
            'create' => Pages\CreateBot::route('/create'),
            'edit' => Pages\AdminBot::route('/{record}/edit'),
            'admin' => Pages\AdminBot::route('/{record}/admin'),
            'button-admin' => Pages\BotMessageButtonAdmin::route('/{bot_message_id}/{id}/button-admin'),
            'chats' => Pages\BotChats::route('/{bot_id}/chats'),
            'chat-admin' => Pages\BotChatAdmin::route('/{bot_id}/{id}/chat-admin'),
            'messages' => Pages\BotMessages::route('/{bot_id}/messages'),
            'message-admin' => Pages\BotMessageAdmin::route('/{bot_id}/{id}/message-admin'),
            'pays' => Pages\BotPays::route('/{bot_id}/pays'),
            'pay-admin' => Pages\BotPayAdmin::route('/{bot_id}/{id}/pay-admin'),
            'pay-guests' => Pages\BotPayGuests::route('/{bot_id}/pay-guests'),
            'pay-guest-admin' => Pages\BotPayGuestAdmin::route('/{bot_id}/{id}/pay-guest-admin'),
            'supergroups' => Pages\BotSupergroups::route('/{bot_id}/supergroups'),
            'supergroup-admin' => Pages\BotSupergroupAdmin::route('/{bot_id}/{id}/supergroup-admin'),
            'products' => Pages\BotProducts::route('/{bot_id}/products'),
            'product-admin' => Pages\BotProductAdmin::route('/{bot_id}/{id}/product-admin'),
            'getcourse-settings' => Pages\BotGetCourseSettings::route('/{bot_id}/getcourse-settings'),
            'getcourse-webhooks' => Pages\BotGetCourseWebhooks::route('/{bot_id}/getcourse-webhooks'),
            'telegram-ban-schedules' => Pages\BotTelegramBanSchedules::route('/{bot_id}/telegram-ban-schedules'),
            'telegram-ban-schedule-admin' => Pages\BotTelegramBanScheduleAdmin::route('/{bot_id}/{id}/telegram-ban-schedule-admin'),
            'telegram-unban-schedules' => Pages\BotTelegramUnBanSchedules::route('/{bot_id}/telegram-unban-schedules'),
            'telegram-unban-schedule-admin' => Pages\BotTelegramUnBanScheduleAdmin::route('/{bot_id}/{id}/telegram-unban-schedule-admin'),
            'funnels' => Pages\BotFunnels::route('/{bot_id}/funnels'),
            'funnel-admin' => Pages\BotFunnelAdmin::route('/{bot_id}/{id}/funnel-admin'),
            'bot-wizard' => Pages\BotWizard::route('/bot-wizard'),
            'pay-system-admin' => Pages\BotPaySystemAdmin::route('/{bot_id}/{id}/pay-system-admin'),
            'pay-systems' => Pages\BotPaySystems::route('/pay-system'),





        ];
    }
}
