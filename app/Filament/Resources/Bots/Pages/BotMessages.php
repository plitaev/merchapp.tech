<?php

namespace App\Filament\Resources\Bots\Pages;

use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Bots\BotResource;
use App\Models\Core\Bot;
use App\Models\Core\BotMessage;
use App\Models\Core\User;

use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

use Filament\Support\Enums\Width;

class BotMessages extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = BotResource::class;

    protected string $view = 'filament.resources.bot-resource.pages.bot-messages';

    public static ?string $label = "Сообщение";
    public static ?string $navigationLabel = "Сообщения";
    public static ?string $title = "";

    public int $bot_id;
    public string $bot_name;

    public function getMaxContentWidth(): Width{return Width::ScreenTwoExtraLarge;}

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
            ->query(BotMessage::where('bot_id', $this->bot_id))
            ->persistSearchInSession()
            ->columns([
                TextColumn::make('name')
                    ->label('Название (Только в панели администратора)')
                    ->searchable(),
                TextColumn::make('bot_message_type.name')
                    ->label('Тип сообщения'),
                TextColumn::make('bot_message_appointment.name')
                    ->label('Назначение'),
                TextColumn::make('bot_branch.name')
                    ->label('Ветка'),
                TextColumn::make('funnel.name')
                    ->label('Воронка'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()->url(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/message-admin")
                    ->visible(fn() => auth()->user()->can('Update:BotMessage')),
                DeleteAction::make()
                    ->visible(fn() => auth()->user()->can('Delete:BotMessage'))
            ])
            ->recordUrl(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/message-admin")
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
