<?php

namespace App\Filament\Resources\MiniApps\Pages;

use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\MiniApps\MiniAppResource;
use App\Models\Core\MiniApp;
use App\Models\Core\Bot;
use App\Models\Core\MiniAppClass;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;


class AdvancedListMiniApp extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = MiniAppResource::class;

    protected string $view = 'filament.resources.mini-app-resource.pages.advanced-list-mini-app';


    public static ?string $label = "Мини-приложения";
    public static ?string $navigationLabel = "Мини-приложения";
    public static ?string $title = "";


    public static function table(Table $table): Table
    {
        return $table
            ->persistSearchInSession()
            ->columns([
                TextColumn::make('name')
                    ->label('Название (Только в панели администратора)')
                    ->searchable(),
                TextColumn::make('url')
                    ->label('URL')
                    ->searchable(),
                TextColumn::make('bot.alias')
                    ->label('Бот приложения'),
                TextColumn::make('bot.name')
                    ->label('Привязанный бот'),
                TextColumn::make('created_at')
                    ->label('Создано')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Изменено')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()
                    ->visible(!auth()->user()->can('Update:MiniApp')),
                EditAction::make()
                    ->visible(auth()->user()->can('Update:MiniApp')),
                DeleteAction::make()
                    ->visible(auth()->user()->can('Delete:MiniApp')),
            ])
            ->recordUrl(fn($record) => "/admin/mini-apps/".$record->id."/admin")
            ->toolbarActions([
                //
            ]);
    }
}


