<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VariableSystemTypeResource\Pages;
use App\Filament\Resources\VariableSystemTypeResource\RelationManagers;
use App\Models\Core\VariableSystemType;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VariableSystemTypeResource extends Resource
{
    protected static ?string $model = VariableSystemType::class;

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
            'index' => Pages\ListVariableSystemTypes::route('/'),
            'create' => Pages\CreateVariableSystemType::route('/create'),
            'edit' => Pages\EditVariableSystemType::route('/{record}/edit'),
        ];
    }
}
