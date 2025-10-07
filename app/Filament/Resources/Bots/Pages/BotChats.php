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
                TextColumn::make('telegram_chat_id')
                    ->label('ID')
                    ->searchable(),
                TextColumn::make('first_name')
                    ->label('Имя')
                    ->searchable(),
                TextColumn::make('last_name')
                    ->label('Фамилия')
                    ->searchable(),
                TextColumn::make('hand_name')
                    ->label('Отчество')
                    ->searchable(),
                TextColumn::make('username')
                    ->label('Имя пользователя')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('ban_name.name')
                    ->label('Бан'),
                TextColumn::make('ban_time')
                    ->label('Дата/время бана'),
                TextColumn::make('unban_name.name')
                    ->label('Разбан'),
                TextColumn::make('unban_time')
                    ->label('Дата/время разбана'),

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
            ->recordActions([
                EditAction::make()->url(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/chat-admin"),
                DeleteAction::make(),

            ])
            ->recordUrl(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/chat-admin")
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

}
