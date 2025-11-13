<?php

namespace App\Filament\Resources\VariableGroups\Pages;

use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
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
    public static ?string $title = "";

    public static function table(Table $table): Table
    {
        return $table
            ->query(VariableGroup::query())
            ->persistSearchInSession()
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
                ViewAction::make()->url(fn($record) => "/admin/variable-groups/".$record->id."/admin")
                    ->visible(!auth()->user()->can('Update:VariableGroup')),
                EditAction::make()->url(fn($record) => "/admin/variable-groups/".$record->id."/admin")
                    ->visible(auth()->user()->can('Update:VariableGroup')),
                DeleteAction::make()
                    ->visible(auth()->user()->can('Delete:VariableGroup')),


            ])
            ->recordUrl(fn($record) => "/admin/variable-groups/".$record->id."/admin")
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

}

