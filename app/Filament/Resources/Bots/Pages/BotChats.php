<?php
namespace App\Filament\Resources\Bots\Pages;

use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Bots\BotResource;
use App\Models\Core\Bot;
use App\Models\Core\BotUser;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Support\Enums\IconPosition;

class BotChats extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = BotResource::class;

    protected string $view = 'filament.resources.bot-resource.pages.bot-chats';

    public static ?string $label = "";
    public static ?string $navigationLabel = "Подписчики";
    public static ?string $title = "Подписчики";

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
            ->query(BotUser::where('bot_id', $this->bot_id))
            ->columns([
                Tables\Columns\TextColumn::make('telegram_chat_id')
                    ->label('ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('first_name')
                    ->icon('heroicon-m-clipboard')
                    ->iconPosition(IconPosition::Before)
                    ->iconColor('gray')
                    ->extraAttributes(fn (BotUser $record) => [
                        'x-data' => '{}',
                        'x-on:click.prevent' => "
                        if (\$event.target.closest('svg')) {
                         navigator.clipboard.writeText('{$record->email}');
                         \$tooltip('Скопировано', { timeout: 1500 });
                        } else {
                         window.location.href = '/admin/bots/".$this->bot_id."/".$record->id."/chat-admin';
                        }"
                    ])
                    ->label('Имя')
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->icon('heroicon-m-clipboard')
                    ->iconPosition(IconPosition::Before)
                    ->iconColor('gray')
                    ->extraAttributes(fn (BotUser $record) => [
                        'x-data' => '{}',
                        'x-on:click.prevent' => "
                        if (\$event.target.closest('svg')) {
                         navigator.clipboard.writeText('{$record->email}');
                         \$tooltip('Скопировано', { timeout: 1500 });
                        } else {
                         window.location.href = '/admin/bots/".$this->bot_id."/".$record->id."/chat-admin';
                        }"
                    ])
                    ->label('Фамилия')
                    ->searchable(),
                Tables\Columns\TextColumn::make('username')
                    ->icon('heroicon-m-clipboard')
                    ->iconPosition(IconPosition::Before)
                    ->iconColor('gray')
                    ->extraAttributes(fn (BotUser $record) => [
                        'x-data' => '{}',
                        'x-on:click.prevent' => "
                        if (\$event.target.closest('svg')) {
                         navigator.clipboard.writeText('{$record->email}');
                         \$tooltip('Скопировано', { timeout: 1500 });
                        } else {
                         window.location.href = '/admin/bots/".$this->bot_id."/".$record->id."/chat-admin';
                        }"
                    ])
                    ->label('Имя пользователя')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->icon('heroicon-m-clipboard')
                    ->iconPosition(IconPosition::Before)
                    ->iconColor('gray')
                    ->extraAttributes(fn (BotUser $record) => [
                        'x-data' => '{}',
                        'x-on:click.prevent' => "
                        if (\$event.target.closest('svg')) {
                         navigator.clipboard.writeText('{$record->email}');
                         \$tooltip('Скопировано', { timeout: 1500 });
                        } else {
                         window.location.href = '/admin/bots/".$this->bot_id."/".$record->id."/chat-admin';
                        }"
                    ])
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_end')
                    ->date('d.m.Y')
                    ->label('Дата подписки'),
                Tables\Columns\TextColumn::make('ban_name.name')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Да' => 'danger',
                        'Нет' => 'success',
                    })
                    ->label('Бан'),
                Tables\Columns\TextColumn::make('ban_time')
                    ->label('Дата/время бана')
            ])
            ->filters([
                Filter::make('Все')
                    ->query(function($query) {
                        return $query;
                    }),
                Filter::make('Забаненные')
                    ->query(function($query) {
                        return $query->where('ban', 1);
                    }),
                Filter::make('Разбаненные')
                    ->query(function($query) {
                        return $query->where('unban', 1);
                    })
            ])
            ->actions([
                EditAction::make()->url(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/chat-admin"),
                DeleteAction::make(),

            ])
            ->recordUrl(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/chat-admin")
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

}
