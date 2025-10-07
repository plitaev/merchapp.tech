<?php

namespace App\Filament\Resources\FunnelConditions;

use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\FunnelConditions\Pages\AdvancedListFunnelCondition;
use App\Filament\Resources\FunnelConditions\Pages\AdminFunnelCondition;
use App\Filament\Resources\FunnelConditionResource\Pages;
use App\Filament\Resources\FunnelConditionResource\RelationManagers;
use App\Models\Core\FunnelCondition;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FunnelConditionResource extends Resource
{
    protected static ?string $model = FunnelCondition::class;

    public static ?string $label = "Условие воронки";
    public static ?string $navigationLabel = "Условие воронки";
    public static ?string $title = "Условие воронки";

    public static function getPluralLabel(): ?string {return "Условие воронки";}

    protected static bool $shouldRegisterNavigation = false;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
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
        return [
            'index' => AdvancedListFunnelCondition::route('/'),
            'create' => AdminFunnelCondition::route('/{id}/admin'),
            'edit' => AdminFunnelCondition::route('/{id}/admin'),
            'admin' => AdminFunnelCondition::route('/{id}/admin'),

        ];
    }
}
