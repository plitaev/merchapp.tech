<?php

namespace App\Filament\Resources\Users\Pages;

use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;

use App\Filament\Resources\Users\UserResource;
use App\Models\Core\User;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class AdvancedListUser extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = UserResource::class;

    protected string $view = 'filament.resources.user-resource.pages.advanced-list-user';

    public static ?string $label = "Ожидания";
    public static ?string $navigationLabel = "Ожидания";
    public static ?string $title = "";

    public static function table(Table $table): Table
    {
        return $table
            ->query(User::query())
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
                ViewAction::make()->url(fn($record) => "/admin/users/".$record->id."/admin")
                    ->visible(!auth()->user()->can('Update:User')),
                EditAction::make()->url(fn($record) => "/admin/users/".$record->id."/admin")
                    ->visible(auth()->user()->can('Update:User')),
                DeleteAction::make()
                    ->visible(auth()->user()->can('Delete:User')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->recordUrl(fn($record) => "/admin/users/".$record->id."/admin");
    }
}

