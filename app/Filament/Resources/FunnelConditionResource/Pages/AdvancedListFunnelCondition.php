<?php

namespace App\Filament\Resources\FunnelConditionResource\Pages;

use App\Filament\Resources\FunnelConditionResource;
use App\Models\Core\FunnelCondition;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class AdvancedListFunnelCondition extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = FunnelConditionResource::class;

    protected static string $view = 'filament.resources.funnel-condition-resource.pages.advanced-list-funnel-condition';

    public static ?string $label = "Условие воронки";
    public static ?string $navigationLabel = "Условие воронки";
    public static ?string $title = "";

    public static function table(Table $table): Table
    {
        return $table
            ->query(FunnelCondition::select('*'))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Наименование')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Создан')
                    ->dateTime('d.m.Y H:i:s')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->url(fn($record) => "/admin/funnel-conditions/".$record->id."/admin"),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}

