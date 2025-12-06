<?php

namespace App\Filament\Resources\Users;

use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Users\Pages\AdvancedListUser;
use App\Filament\Resources\Users\Pages\AdminUser;
use App\Filament\Resources\Users\Pages\AdminModelHasPermission;
use App\Filament\Resources\ListenerResource\Pages;
use App\Filament\Resources\ListenerResource\RelationManagers;
use App\Models\Core\User;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    public static ?string $label = "Пользователи";
    public static ?string $navigationLabel = "Пользователи";
    public static ?string $title = "Пользователи";

    public static function getPluralLabel(): ?string {return "Пользователи";}

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
            'index' => AdvancedListUser::route('/'),
            'create' => AdminUser::route('/{id}/admin'),
            'edit' => AdminUser::route('/{id}/admin'),
            'admin' => AdminUser::route('/{id}/admin'),
            'model-has-permission' => AdminModelHasPermission::route('/{id}/model-has-permission'),


        ];
    }
}
