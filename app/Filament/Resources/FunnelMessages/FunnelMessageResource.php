<?php

namespace App\Filament\Resources\FunnelMessages;

use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\FunnelMessages\Pages\AdvancedListFunnelMessage;
use App\Filament\Resources\FunnelMessages\Pages\AdminFunnelMessage;
use App\Filament\Resources\FunnelMessageResource\Pages;
use App\Filament\Resources\FunnelMessageResource\RelationManagers;
use App\Models\Core\FunnelMessage;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FunnelMessageResource extends Resource
{
    protected static ?string $model = FunnelMessage::class;

    public static ?string $label = "Сообщения в воронке";
    public static ?string $navigationLabel = "Сообщения в воронке";
    public static ?string $title = "Сообщения в воронке";

    public static function getPluralLabel(): ?string {return "Сообщения в воронке";}

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
            'index' => AdvancedListFunnelMessage::route('/'),
            'create' => AdminFunnelMessage::route('/{id}/admin'),
            'edit' => AdminFunnelMessage::route('/{id}/admin'),
            'admin' => AdminFunnelMessage::route('/{id}/admin'),

        ];
    }
}
