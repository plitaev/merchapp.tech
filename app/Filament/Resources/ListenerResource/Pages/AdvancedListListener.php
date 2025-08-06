<?php

namespace App\Filament\Resources\ListenerResource\Pages;

use App\Filament\Resources\ListenerResource;
use App\Models\Core\Listener;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class AdvancedListListener extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = ListenerResource::class;

    protected static string $view = 'filament.resources.listener-resource.pages.advanced-list-listener';

    public static ?string $label = "Ожидания";
    public static ?string $navigationLabel = "Ожидания";
    public static ?string $title = "";

    public static function table(Table $table): Table
    {
        return $table
            ->query(Listener::select('*'))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Наименование')
                    ->searchable(),
                Tables\Columns\TextColumn::make('alias')
                    ->label('Alias')
                    ->searchable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->url(fn($record) => "/admin/listeners/".$record->id."/admin"),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}

