<?php

namespace App\Filament\Resources\Listeners;

use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Listeners\Pages\AdvancedListListener;
use App\Filament\Resources\Listeners\Pages\AdminListener;
use App\Filament\Resources\ListenerResource\Pages;
use App\Filament\Resources\ListenerResource\RelationManagers;
use App\Models\Core\Listener;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ListenerResource extends Resource
{
    protected static ?string $model = Listener::class;

    public static ?string $label = "Ожидания";
    public static ?string $navigationLabel = "Ожидания";
    public static ?string $title = "Ожидания";

    public static function getPluralLabel(): ?string {return "Ожидания";}

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
            'index' => AdvancedListListener::route('/'),
            'create' => AdminListener::route('/{id}/admin'),
            'edit' => AdminListener::route('/{id}/admin'),
            'admin' => AdminListener::route('/{id}/admin'),

        ];
    }
}
