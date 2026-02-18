<?php

namespace App\Filament\Resources\MiniAppBlockTypes;

use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\MiniAppBlockTypes\Pages\AdvancedListMiniAppBlockType;
use App\Filament\Resources\MiniAppBlockTypes\Pages\AdminMiniAppBlockType;
use App\Filament\Resources\MiniAppBlockTypeResource\Pages;
use App\Filament\Resources\MiniAppBlockTypeResource\RelationManagers;
use App\Models\Core\MiniAppBlockType;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MiniAppBlockTypeResource extends Resource
{
    protected static ?string $model = MiniAppBlockType::class;

    public static ?string $label = "Тип блока Мини-приложения";
    public static ?string $navigationLabel = "Тип блока Мини-приложения";
    public static ?string $title = "Тип блока Мини-приложения";

    public static function getPluralLabel(): ?string {return "Тип блока Мини-приложения";}

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
            'index' => AdvancedListMiniAppBlockType::route('/'),
            'create' => AdminMiniAppBlockType::route('/{id}/admin'),
            'edit' => AdminMiniAppBlockType::route('/{id}/admin'),
            'admin' => AdminMiniAppBlockType::route('/{id}/admin'),

        ];
    }
}
