<?php

namespace App\Filament\Resources\FunnelConditions\Pages;

use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\FunnelConditions\FunnelConditionResource;
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

    protected string $view = 'filament.resources.funnel-condition-resource.pages.advanced-list-funnel-condition';

    public static ?string $label = "Условие воронки";
    public static ?string $navigationLabel = "Условие воронки";
    public static ?string $title = "";

    public static function table(Table $table): Table
    {
        return $table
            ->query(FunnelCondition::query())
            ->persistSearchInSession()
            ->columns([
                TextColumn::make('name')
                    ->label('Наименование')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Создан')
                    ->dateTime('d.m.Y H:i:s')
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()->url(fn($record) => "/admin/funnel-conditions/".$record->id."/admin"),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->recordUrl(fn($record) => "/admin/funnel-conditions/".$record->id."/admin");
    }
}

