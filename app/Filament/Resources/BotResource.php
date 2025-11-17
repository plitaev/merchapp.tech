<?php
namespace App\Filament\Resources\Bots;

use App\Filament\Resources\Bots\Pages\BotShopSegments;
use Illuminate\Contracts\View\View;

use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;

use Filament\Forms;
use Filament\Forms\Components\TextInput;

use Filament\Resources\Action as FRActon;
use Filament\Resources\Resource;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

use Filament\Schemas\Schema;

use Filament\Support\Enums\Width;

use App\Filament\Resources\Bots\Pages\AdvancedListBot;
use App\Filament\Resources\Bots\Pages\CreateBot;
use App\Filament\Resources\Bots\Pages\AdminBot;
use App\Filament\Resources\Bots\Pages\BotAdmin;
use App\Filament\Resources\Bots\Pages\BotMessageButtonAdmin;
use App\Filament\Resources\Bots\Pages\BotChats;
use App\Filament\Resources\Bots\Pages\BotChatAdmin;
use App\Filament\Resources\Bots\Pages\BotMessages;
use App\Filament\Resources\Bots\Pages\BotMessageAdmin;
use App\Filament\Resources\Bots\Pages\BotPays;
use App\Filament\Resources\Bots\Pages\BotPayAdmin;
use App\Filament\Resources\Bots\Pages\BotPayGuests;
use App\Filament\Resources\Bots\Pages\BotPayGuestAdmin;
use App\Filament\Resources\Bots\Pages\BotPaySystemAdmin;
use App\Filament\Resources\Bots\Pages\BotSendings;
use App\Filament\Resources\Bots\Pages\BotSendingAdmin;
use App\Filament\Resources\Bots\Pages\BotSendingSome;
use App\Filament\Resources\Bots\Pages\BotSupergroups;
use App\Filament\Resources\Bots\Pages\BotSupergroupAdmin;
use App\Filament\Resources\Bots\Pages\BotProducts;
use App\Filament\Resources\Bots\Pages\BotProductAdmin;
use App\Filament\Resources\Bots\Pages\BotGetCourseSettings;
use App\Filament\Resources\Bots\Pages\BotGetCourseWebhooks;
use App\Filament\Resources\Bots\Pages\BotTelegramBanSchedules;
use App\Filament\Resources\Bots\Pages\BotTelegramBanScheduleAdmin;
use App\Filament\Resources\Bots\Pages\BotTelegramUnBanSchedules;
use App\Filament\Resources\Bots\Pages\BotTelegramUnBanScheduleAdmin;
use App\Filament\Resources\Bots\Pages\BotFunnels;
use App\Filament\Resources\Bots\Pages\BotFunnelAdmin;
use App\Filament\Resources\Bots\Pages\BotWizard;
use App\Filament\Resources\Bots\Pages\BotBranches;
use App\Filament\Resources\Bots\Pages\BotBranchAdmin;
use App\Filament\Resources\Bots\Pages\TelegramSendMessageLogs;
use App\Filament\Resources\Bots\Pages\BotMessageListeners;
use App\Filament\Resources\Bots\Pages\BotTelegramBanScheduleLogs;
use App\Filament\Resources\Bots\Pages\BotTelegramUnBanScheduleLogs;
use App\Filament\Resources\Bots\Pages\BotTelegramBanScheduleErrorLogs;
use App\Filament\Resources\Bots\Pages\BotTelegramUnBanScheduleErrorLogs;
use App\Filament\Resources\Bots\Pages\BotTelegramChatMemberErrorLogs;
use App\Filament\Resources\Bots\Pages\BotTelegramSendMessageErrorLogs;
use App\Filament\Resources\Bots\Pages\Access;

use App\Models\Core\Bot;

use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Traits\HasPermissions;

class BotResource extends Resource
{
    protected static ?string $model = Bot::class;
    public static ?string $label = "Бот";
    public static ?string $navigationLabel = "Боты";
    public static ?string $title = "Боты";

    protected static bool $shouldRegisterNavigation = false;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-chat-bubble-oval-left';

    public static ?int $navigationSort = 2;

    public static function getPluralLabel(): ?string {return "Боты";}

    public function getMaxContentWidth(): Width{return Width::Full;}

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Название (Только в панели администратора)')
                    ->required()
                    ->maxLength(255),
                TextInput::make('alias')
                    ->label('Username в Telegram')
                    ->required()
                    ->maxLength(255),
                TextInput::make('telegram_token')
                    ->label('Telegram-токен (из BotFather)')
                    ->required()
                    ->maxLength(255),
                TextInput::make('telegram_webhook')
                    ->label('Адрес вебхука Telegram')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Название (Только в панели администратора)')
                    ->searchable(),
                TextColumn::make('alias')
                    ->label('Username в Telegram')
                    ->searchable(),
                TextColumn::make('telegram_token')
                    ->label('Telegram-токен')
                    ->searchable(),
                IconColumn::make('telegram_webhook')
                    ->label('Статус')
                    ->extraAttributes(['class' => 'flex justify-center'])
                    ->options(['heroicon-o-cog-8-tooth'])
                    ->action(
                        \Filament\Actions\Action
                            ::make('telegram_webhook')
                            ->action(fn (Bot $record) => $record->advance())
                            ->modalContent(fn (Bot $record): View => view(
                                'core.filament.bot.telegram-webhook-set-second',
                                ['record' => $record]
                            ))
                            ->modalSubmitAction(false)
                            ->modalCancelAction(false)
                    ),
                TextColumn::make('created_at')
                    ->label('Создано')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Изменено')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
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
        $A = [
            'index' => AdvancedListBot::route('/'),
            'create' => CreateBot::route('/create'),
            'edit' => AdminBot::route('/{record}/edit'),
            'admin' => AdminBot::route('/{record}/admin'),
            'bot-admin' => BotAdmin::route('/{id}/bot-admin'),
            'bot-wizard' => BotWizard::route('/bot-wizard'),
            'button-admin' => BotMessageButtonAdmin::route('/{bot_message_id}/{id}/button-admin'),
            'chats' => BotChats::route('/{bot_id}/chats'),
            'chat-admin' => BotChatAdmin::route('/{bot_id}/{id}/chat-admin'),
            'telegram-send-message-logs' => TelegramSendMessageLogs::route('/{bot_id}/{bot_user_id}/telegram-send-message-logs'),
            'telegram-ban-schedule-logs' => BotTelegramBanScheduleLogs::route('/{bot_id}/{bot_user_id}/telegram-ban-schedule-logs'),
            'telegram-unban-schedule-logs' => BotTelegramUnBanScheduleLogs::route('/{bot_id}/{bot_user_id}/telegram-unban-schedule-logs'),
            'bot-telegram-ban-schedule-error-logs' => BotTelegramBanScheduleErrorLogs::route('/{bot_id}/{bot_user_id}/telegram-ban-schedule-error-logs'),
            'bot-telegram-unban-schedule-error-logs' => BotTelegramUnBanScheduleErrorLogs::route('/{bot_id}/{bot_user_id}/telegram-unban-schedule-error-logs'),
            'bot-telegram-chat-member-error-logs' => BotTelegramChatMemberErrorLogs::route('/{bot_id}/{bot_user_id}/telegram-chat-member-error-logs'),
            'bot-telegram-send-message-error-logs' => BotTelegramSendMessageErrorLogs::route('/{bot_id}/{bot_user_id}/telegram-send-message-error-logs'),
            'message-listeners' => BotMessageListeners::route('/{bot_id}/{id}/message-listeners'),
            'funnels' => BotFunnels::route('/{bot_id}/funnels'),
            'funnel-admin' => BotFunnelAdmin::route('/{bot_id}/{id}/funnel-admin'),
            'getcourse-settings' => BotGetCourseSettings::route('/{bot_id}/getcourse-settings'),
            'getcourse-webhooks' => BotGetCourseWebhooks::route('/{bot_id}/getcourse-webhooks'),
            'messages' => BotMessages::route('/{bot_id}/messages'),
            'message-admin' => BotMessageAdmin::route('/{bot_id}/{id}/message-admin'),
            'pays' => BotPays::route('/{bot_id}/pays'),
            'pay-admin' => BotPayAdmin::route('/{bot_id}/{id}/pay-admin'),
            'pay-system-admin' => BotPaySystemAdmin::route('/{record}/pay-system-admin'),
            'pay-guests' => BotPayGuests::route('/{bot_id}/pay-guests'),
            'pay-guest-admin' => BotPayGuestAdmin::route('/{bot_id}/{id}/pay-guest-admin'),
            'products' => BotProducts::route('/{bot_id}/products'),
            'product-admin' => BotProductAdmin::route('/{bot_id}/{id}/product-admin'),
            'sendings' => BotSendings::route('/{bot_id}/sendings'),
            'sending-admin' => BotSendingAdmin::route('/{bot_id}/{id}/sending-admin'),
            'sending-some' => BotSendingSome::route('/{bot_id}/{id}/sending-some'),
            'supergroups' => BotSupergroups::route('/{bot_id}/supergroups'),
            'supergroup-admin' => BotSupergroupAdmin::route('/{bot_id}/{id}/supergroup-admin'),
            'telegram-ban-schedules' => BotTelegramBanSchedules::route('/{bot_id}/telegram-ban-schedules'),
            'telegram-ban-schedule-admin' => BotTelegramBanScheduleAdmin::route('/{bot_id}/{id}/telegram-ban-schedule-admin'),
            'telegram-unban-schedules' => BotTelegramUnBanSchedules::route('/{bot_id}/telegram-unban-schedules'),
            'telegram-unban-schedule-admin' => BotTelegramUnBanScheduleAdmin::route('/{bot_id}/{id}/telegram-unban-schedule-admin'),
            'branches' => BotBranches::route('/{bot_id}/branches'),
            'branch-admin' => BotBranchAdmin::route('/{bot_id}/{id}/branch-admin'),
            'shop-segments' => BotShopSegments::route('/{bot_id}/{id}/shop-segments'),
            'access' => Access::route('/access'),
        ];

        return $A;
    }
}
