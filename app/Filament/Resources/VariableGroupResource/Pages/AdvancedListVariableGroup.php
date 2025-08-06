<?php

namespace App\Filament\Resources\VariableGroupResource\Pages;

use App\Filament\Resources\VariableGroupResource;
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

    protected static string $view = 'filament.resources.variable-group-resource.pages.advanced-list-variable-group';


    public static ?string $label = "Переменные";
    public static ?string $navigationLabel = "Переменные";
    public static ?string $title = "";

    public static function table(Table $table): Table
    {
        return $table
            ->query(VariableGroup::query())
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Название')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Описание'),
                Tables\Columns\TextColumn::make('alias')
                    ->label('Псевдоним')
                    ->searchable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->url(fn($record) => "/admin/variable-groups/".$record->id."/admin"),
                Tables\Actions\DeleteAction::make()

            ])
            ->recordUrl(fn($record) => "/admin/variable-groups/".$record->id."/admin")
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

}

