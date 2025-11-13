<?php

namespace App\Filament\Resources\MiniApps;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use App\Filament\Resources\MiniApps\Pages\ListMiniApps;
use App\Filament\Resources\MiniApps\Pages\CreateMiniApp;
use App\Filament\Resources\MiniApps\Pages\EditMiniApp;
use App\Filament\Resources\MiniApps\Pages\ViewMiniApp;
use App\Filament\Resources\MiniAppResource\Pages;
use App\Filament\Resources\MiniAppResource\RelationManagers;
use App\Models\Core\Bot;
use App\Models\Core\MiniApp;
use App\Models\Core\MiniAppClass;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\ViewAction;

class MiniAppResource extends Resource
{
    protected static ?string $model = MiniApp::class;

    public static ?string $label = "Мини-приложения";
    public static ?string $navigationLabel = "Мини-приложения";
    public static ?string $title = "Мини-приложения";

    protected static bool $shouldRegisterNavigation = false;

    public static ?int $navigationSort = 1;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getPluralLabel(): ?string {return "Мини-приложения";}

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Название (Только в панели администратора)')
                    ->required()
                    ->disabled(auth()->user()->hasPermissionTo('Update:MiniApp')?false:true)
                    ->maxLength(255),
                TextInput::make('url')
                    ->label('URL')
                    ->required()
                    ->disabled(auth()->user()->hasPermissionTo('Update:MiniApp')?false:true)
                    ->maxLength(255),
                Select::make('bot_id')
                    ->label('Привязанный бот')
                    ->required()
                    ->options(Bot::all()->pluck('name', 'id'))
                    ->disabled(auth()->user()->hasPermissionTo('Update:MiniApp')?false:true)
                    ->searchable(),
                Select::make('class_id')
                    ->label('Тип мини-приложения')
                    ->required()
                    ->options(MiniAppClass::all()->pluck('name', 'id'))
                    ->disabled(auth()->user()->hasPermissionTo('Update:MiniApp')?false:true)
                    ->searchable(),
            ]);
    }

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
            ->recordAction('view')
            ->toolbarActions([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMiniApps::route('/'),
            'create' => CreateMiniApp::route('/create'),
            'edit' => EditMiniApp::route('/{record}/edit'),
            'view' => ViewMiniApp::route('/{record}'),
        ];
    }
}
