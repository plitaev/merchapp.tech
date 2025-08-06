<?php
namespace App\Filament\Resources\BotResource\Pages;

use App\Filament\Resources\BotResource;
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

    protected static string $view = 'filament.resources.bot-resource.pages.advanced-list-bot';

    public static ?string $label = "Боты";
    public static ?string $navigationLabel = "Боты";
    public static ?string $title = "";

    public int $category = 1;

    public static function table(Table $table): Table
    {
        return $table
            ->query(Bot::query())
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
                                'core.filament.bot.telegram-webhook-set',
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
                Tables\Actions\EditAction::make()->url(fn($record) => "/admin/bots/".$record->id."/edit"),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                //
            ])
            ->recordUrl(fn($record) => "/admin/bots/".$record->id."/edit");
    }
}
