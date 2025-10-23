<?php

namespace App\Filament\Resources\Users\Pages;

use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Listeners\ListenerResource;
use App\Models\Core\Listener;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class AdvancedListUser extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = UserResource::class;

    protected string $view = 'filament.resources.user-resource.pages.advanced-list-listener';

    public static ?string $label = "Ожидания";
    public static ?string $navigationLabel = "Ожидания";
    public static ?string $title = "";

    public static function table(Table $table): Table
    {
        return $table
            ->query(Listener::select('*'))
            ->columns([
                TextColumn::make('name')
                    ->label('Наименование')
                    ->searchable(),
                TextColumn::make('alias')
                    ->label('Alias')
                    ->searchable()
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()->url(fn($record) => "/admin/listeners/".$record->id."/admin"),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->recordUrl(fn($record) => "/admin/listeners/".$record->id."/admin");
    }
}

