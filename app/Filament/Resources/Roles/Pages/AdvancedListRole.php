<?php

namespace App\Filament\Resources\Roles\Pages;

use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;

use App\Filament\Resources\Roles\RoleResource;
use App\Models\Core\Role;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class AdvancedListRole extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = RoleResource::class;

    protected string $view = 'filament.resources.role-resource.pages.advanced-list-role';

    public static ?string $label = "Ожидания";
    public static ?string $navigationLabel = "Ожидания";
    public static ?string $title = "";

    public static function table(Table $table): Table
    {
        return $table
            ->query(Role::query())
            ->persistSearchInSession()
            ->columns([
                TextColumn::make('id')
                    ->label('ID'),
                TextColumn::make('name')
                    ->label('Имя')
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()->url(fn($record) => "/admin/roles/".$record->id."/admin")
                    ->visible(!auth()->user()->can('Update:Role')),
                EditAction::make()->url(fn($record) => "/admin/roles/".$record->id."/admin")
                    ->visible(auth()->user()->can('Update:Role')),
                DeleteAction::make()
                    ->visible(!auth()->user()->can('Delete:Role')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->recordUrl(fn($record) => "/admin/roles/".$record->id."/admin");
    }
}

