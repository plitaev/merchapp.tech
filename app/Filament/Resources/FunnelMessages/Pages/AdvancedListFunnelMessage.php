<?php

namespace App\Filament\Resources\FunnelMessages\Pages;

use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\FunnelMessages\FunnelMessageResource;
use App\Models\Core\FunnelMessage;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class AdvancedListFunnelMessage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = FunnelMessageResource::class;

    protected string $view = 'filament.resources.funnel-message-resource.pages.advanced-list-funnel-message';


    public static ?string $label = "Воронка сообщений";
    public static ?string $navigationLabel = "Воронка сообщений";
    public static ?string $title = "";

    public static function table(Table $table): Table
    {
        return $table
            ->query(FunnelMessage::with('funnel')->with('bot_message')->with('funnel_condition'))
            ->columns([
                TextColumn::make('funnel.name')
                    ->label('Наименование воронки')
                    ->searchable(),
                TextColumn::make('funnel_condition.name')
                    ->label('Наименование состояния воронки')
                    ->searchable(),
                TextColumn::make('bot_message.name')
                    ->label('Наименование бота сообщений')
                    ->searchable(),
                TextColumn::make('days_before_condition')
                    ->label('Дни до состояния')
                    ->searchable(),
                TextColumn::make('hours_before_condition')
                    ->label('Часы до состояния')
                    ->searchable(),
                TextColumn::make('minutes_before_condition')
                    ->label('Минуты до состояния')
                    ->searchable(),
                TextColumn::make('days_after_condition')
                    ->label('Дни после состояния')
                    ->searchable(),
                TextColumn::make('hours_after_condition')
                    ->label('Часы после состояния')
                    ->searchable(),
                TextColumn::make('minutes_after_condition')
                    ->label('Минуты после состояния')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Создан')
                    ->dateTime('d.m.Y H:i:s')
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()->url(fn($record) => "/admin/funnel-messages/".$record->id."/admin"),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}



