<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VariablesSystemResource\Pages;
use App\Filament\Resources\VariablesSystemResource\RelationManagers;
use App\Models\Core\VariablesSystem;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VariablesSystemResource extends Resource
{
    protected static ?string $model = VariablesSystem::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static bool $shouldRegisterNavigation = false;

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
            'index' => Pages\ListVariablesSystems::route('/'),
            'create' => Pages\CreateVariablesSystem::route('/create'),
            'edit' => Pages\EditVariablesSystem::route('/{record}/edit'),
            'admin' => Pages\AdminVariablesSystem::route('/{id}/{variable_group_id}/admin'),

        ];
    }
}
