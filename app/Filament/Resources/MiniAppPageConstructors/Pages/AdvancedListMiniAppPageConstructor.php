<?php

namespace App\Filament\Resources\MiniAppPageConstructors\Pages;

use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\MiniAppPageConstructors\MiniAppPageConstructorResource;
use App\Models\Core\MiniAppPage;
use App\Models\Core\Bot;
use App\Models\Core\MiniAppPageClass;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;


class AdvancedListMiniAppPageConstructor extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = MiniAppPageConstructorResource::class;

    protected string $view = 'filament.resources.mini-app-page-constructor-resource.pages.advanced-list-mini-app-page-constructor';


    public static ?string $label = "Мини-приложения";
    public static ?string $navigationLabel = "Мини-приложения";
    public static ?string $title = "";

    public function mount(): void
    {
        if (!auth()->user()->hasPermissionTo('View:MiniAppPage')) {
            redirect('/admin/bots/access');
        }

    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(MiniAppPage::select('*'))
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
                ViewAction::make()->url(fn($record) => "/admin/mini-app-page-constructors/".$record->id."/admin")
                    ->visible(!auth()->user()->can('Update:MiniAppPage')),
                EditAction::make()->url(fn($record) => "/admin/mini-app-page-constructors/".$record->id."/admin")
                    ->visible(auth()->user()->can('Update:MiniAppPage')),
                DeleteAction::make()
                    ->visible(auth()->user()->can('Delete:MiniAppPage')),
            ])
            ->recordUrl(fn($record) => "/admin/mini-app-page-constructors/".$record->id."/admin")
            ->toolbarActions([
                //
            ]);
    }
}

