<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BotMessageButtonCallbackResource\Pages;
use App\Filament\Resources\BotMessageButtonCallbackResource\RelationManagers;
use App\Models\BotMessageButtonCallback\BotMessageButtonCallback;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
class BotMessageButtonCallbackResource extends Resource
{
    protected static ?string $model = BotMessageButtonCallback::class;

    public static ?string $label = "Обработчики кнопок";
    public static ?string $navigationLabel = "Обработчики кнопок";
    public static ?string $title = "Обработчики кнопок";

    public static function getPluralLabel(): ?string {return "Обработчики кнопок";}

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
            'index' => Pages\AdvancedListBotMessageButtonCallback::route('/'),
            'create' => Pages\AdminBotMessageButtonCallback::route('/{id}/admin'),
            'edit' => Pages\AdminBotMessageButtonCallback::route('/{id}/admin'),
            'admin' => Pages\AdminBotMessageButtonCallback::route('/{id}/admin'),

        ];
    }
}
