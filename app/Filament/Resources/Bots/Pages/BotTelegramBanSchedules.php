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
use App\Models\Core\BotUserBanSchedule;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;


class BotTelegramBanSchedules extends Page implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;


    protected static string $resource = BotResource::class;

    protected string $view = 'filament.resources.bot-resource.pages.bot-telegram-ban-schedules';


    public static ?string $label = "Баны";
    public static ?string $navigationLabel = "Баны";
    public static ?string $title = "";

    public int $bot_id;
    public string $bot_name;

    public function mount(int $bot_id): void
    {
        $this->bot_id = $bot_id;
        $bot = Bot::select('name')->find($bot_id);

        $this->bot_name = $bot->name;
    }

    protected function getForms(): array
    {
        return ['form'];
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
            ->defaultSort('updated_at', 'desc')
            ->query(
                BotUserBanSchedule::whereHas('bot_user', function ($query) {
                    $query->where('bot_id', $this->bot_id);
                })
            )
            ->columns([
                TextColumn::make('bot_user.first_name')
                    ->label('Имя')
                    ->searchable(),
                TextColumn::make('bot_user.last_name')
                    ->label('Фамилия')
                    ->searchable(),
                TextColumn::make('bot_user.username')
                    ->label('Ник')
                    ->searchable(),
                TextColumn::make('bot_user.email')
                    ->label('Email'),
                TextColumn::make('ban_datetime')
                    ->label('Дата и время бана1')
                    ->dateTime('d.m.Y H:i:s')
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()->url(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/telegram-ban-schedule-admin"),
                DeleteAction::make(),

            ])
            ->recordUrl(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/telegram-ban-schedule-admin")
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
