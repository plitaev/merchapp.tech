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
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $model = VariableGroup::class;

    public static ?string $label = "Группы переменных";
    public static ?string $navigationLabel = "Переменные";
    public static ?string $title = "Группы переменных";
    public static function getPluralLabel(): ?string {return "Группы переменных";}

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
