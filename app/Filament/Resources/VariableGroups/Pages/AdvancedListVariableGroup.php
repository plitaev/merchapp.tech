<?php

namespace App\Filament\Resources\VariableGroups\Pages;

use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\VariableGroups\VariableGroupResource;
use App\Models\Core\VariableGroup;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class AdvancedListVariableGroup extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = VariableGroupResource::class;

    protected string $view = 'filament.resources.variable-group-resource.pages.advanced-list-variable-group';


    public static ?string $label = "Переменные";
    public static ?string $navigationLabel = "Переменные";
    public static ?string $title = "";

    public static function table(Table $table): Table
    {
        return $table
            ->query(VariableGroup::query())
            ->columns([
                TextColumn::make('name')
                    ->label('Название')
                    ->searchable(),
                TextColumn::make('description')
                    ->label('Описание'),
                TextColumn::make('alias')
                    ->label('Псевдоним')
                    ->searchable(),

            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()->url(fn($record) => "/admin/variable-groups/".$record->id."/admin"),
                DeleteAction::make()

            ])
            ->recordUrl(fn($record) => "/admin/variable-groups/".$record->id."/admin")
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

}

