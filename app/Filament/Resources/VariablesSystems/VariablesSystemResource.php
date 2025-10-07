<?php

namespace App\Filament\Resources\VariablesSystems;

use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\VariablesSystems\Pages\ListVariablesSystems;
use App\Filament\Resources\VariablesSystems\Pages\CreateVariablesSystem;
use App\Filament\Resources\VariablesSystems\Pages\EditVariablesSystem;
use App\Filament\Resources\VariablesSystems\Pages\AdminVariablesSystem;
use App\Filament\Resources\VariablesSystemResource\Pages;
use App\Filament\Resources\VariablesSystemResource\RelationManagers;
use App\Models\Core\VariablesSystem;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VariablesSystemResource extends Resource
{
    protected static ?string $model = VariablesSystem::class;

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
            'index' => ListVariablesSystems::route('/'),
            'create' => CreateVariablesSystem::route('/create'),
            'edit' => EditVariablesSystem::route('/{record}/edit'),
            'admin' => AdminVariablesSystem::route('/{id}/{variable_group_id}/admin'),

        ];
    }
}
