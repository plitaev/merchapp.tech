<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VariableGroupResource\Pages;
use App\Filament\Resources\VariableGroupResource\RelationManagers;
use App\Models\Core\VariableGroup;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VariableGroupResource extends Resource
{

    public static ?string $label = "Переменные";
    public static ?string $navigationLabel = "Переменные";
    public static ?string $title = "Переменные";

    public static function getPluralLabel(): ?string {return "Переменные";}
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $model = VariableGroup::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-variable';

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
            'index' => Pages\AdvancedListVariableGroup::route('/'),
            'create' => Pages\AdminVariableGroup::route('/{id}/admin'),
            'edit' => Pages\AdminVariableGroup::route('/{id}/admin'),
            'admin' => Pages\AdminVariableGroup::route('/{id}/admin'),
        ];
    }
}
