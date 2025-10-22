<?php
namespace App\Filament\Resources\Bots\Pages;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use App\Filament\Resources\Bots\BotResource;
use App\Models\Core\Bot;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;


class AdvancedListBot extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = BotResource::class;

    protected string $view = 'filament.resources.bot-resource.pages.advanced-list-bot';

    public static ?string $label = "Боты";
    public static ?string $navigationLabel = "Боты";
    public static ?string $title = "";

    public int $category = 1;

    public static function table(Table $table): Table
    {
        return $table
            ->query(Bot::query())
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
                        Action
                            ::make('telegram_webhook')
                            ->action(fn (Bot $record) => $record->advance())
                            ->modalContent(fn (Bot $record): View => view(
                                'core.filament.bot.telegram-webhook-set',
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
                EditAction::make()->url(fn($record) => "/admin/bots/".$record->id."/edit"),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                //
            ])
            ->recordUrl(fn($record) => "/admin/bots/".$record->id."/edit");
    }
}
