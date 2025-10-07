<?php

namespace App\Filament\Resources\VariableSystemTypes;

use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\VariableSystemTypes\Pages\ListVariableSystemTypes;
use App\Filament\Resources\VariableSystemTypes\Pages\CreateVariableSystemType;
use App\Filament\Resources\VariableSystemTypes\Pages\EditVariableSystemType;
use App\Filament\Resources\VariableSystemTypeResource\Pages;
use App\Filament\Resources\VariableSystemTypeResource\RelationManagers;
use App\Models\Core\VariableSystemType;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VariableSystemTypeResource extends Resource
{
    protected static ?string $model = VariableSystemType::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static bool $shouldRegisterNavigation = false;

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
            'index' => ListVariableSystemTypes::route('/'),
            'create' => CreateVariableSystemType::route('/create'),
            'edit' => EditVariableSystemType::route('/{record}/edit'),
        ];
    }
}
