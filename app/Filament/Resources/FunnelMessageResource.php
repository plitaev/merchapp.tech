<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FunnelMessageResource\Pages;
use App\Filament\Resources\FunnelMessageResource\RelationManagers;
use App\Models\Core\FunnelMessage;
use Filament\Forms\Form;
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
            'index' => Pages\AdvancedListFunnelMessage::route('/'),
            'create' => Pages\AdminFunnelMessage::route('/{id}/admin'),
            'edit' => Pages\AdminFunnelMessage::route('/{id}/admin'),
            'admin' => Pages\AdminFunnelMessage::route('/{id}/admin'),

        ];
    }
}
