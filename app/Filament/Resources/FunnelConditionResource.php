<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FunnelConditionResource\Pages;
use App\Filament\Resources\FunnelConditionResource\RelationManagers;
use App\Models\Core\FunnelCondition;
use Filament\Forms\Form;
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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\AdvancedListFunnelCondition::route('/'),
            'create' => Pages\AdminFunnelCondition::route('/{id}/admin'),
            'edit' => Pages\AdminFunnelCondition::route('/{id}/admin'),
            'admin' => Pages\AdminFunnelCondition::route('/{id}/admin'),

        ];
    }
}
