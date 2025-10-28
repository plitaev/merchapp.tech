<?php

namespace App\Filament\Resources\VariableGroups;

use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\VariableGroups\Pages\AdvancedListVariableGroup;
use App\Filament\Resources\VariableGroups\Pages\AdminVariableGroup;
use App\Filament\Resources\VariableGroupResource\Pages;
use App\Filament\Resources\VariableGroupResource\RelationManagers;
use App\Models\Core\VariableGroup;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VariableGroupResource extends Resource
{

    public static ?string $label = "Переменные";
    public static ?string $navigationLabel = "Переменные";
    public static ?string $title = "Переменные";
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $model = VariableGroup::class;

    protected static ?string $modelLabel = "";

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-variable';

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
            'index' => AdvancedListVariableGroup::route('/'),
            'create' => AdminVariableGroup::route('/{id}/admin'),
            'edit' => AdminVariableGroup::route('/{id}/admin'),
            'admin' => AdminVariableGroup::route('/{id}/admin'),
        ];
    }
}
