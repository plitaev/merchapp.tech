<?php

namespace App\Filament\Resources\BotMessageButtonCallbacks;

use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\BotMessageButtonCallbacks\Pages\AdvancedListBotMessageButtonCallback;
use App\Filament\Resources\BotMessageButtonCallbacks\Pages\AdminBotMessageButtonCallback;
use App\Filament\Resources\BotMessageButtonCallbackResource\Pages;
use App\Filament\Resources\BotMessageButtonCallbackResource\RelationManagers;
use App\Models\BotMessageButtonCallback\BotMessageButtonCallback;
use Filament\Forms;
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
            'index' => AdvancedListBotMessageButtonCallback::route('/'),
            'create' => AdminBotMessageButtonCallback::route('/{id}/admin'),
            'edit' => AdminBotMessageButtonCallback::route('/{id}/admin'),
            'admin' => AdminBotMessageButtonCallback::route('/{id}/admin'),

        ];
    }
}
