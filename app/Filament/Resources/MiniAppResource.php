<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MiniAppResource\Pages;
use App\Filament\Resources\MiniAppResource\RelationManagers;
use App\Models\Core\Bot;
use App\Models\Core\MiniApp;
use App\Models\Core\MiniAppClass;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MiniAppResource extends Resource
{
    protected static ?string $model = MiniApp::class;

    public static ?string $label = "Мини-приложения";
    public static ?string $navigationLabel = "Мини-приложения";
    public static ?string $title = "Мини-приложения";

    protected static bool $shouldRegisterNavigation = false;

    public static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getPluralLabel(): ?string {return "Мини-приложения";}

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Название (Только в панели администратора)')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('url')
                    ->label('URL')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('bot_id')
                    ->label('Привязанный бот')
                    ->required()
                    ->options(Bot::all()->pluck('name', 'id'))
                    ->searchable(),
                Forms\Components\Select::make('class_id')
                    ->label('Тип мини-приложения')
                    ->required()
                    ->options(MiniAppClass::all()->pluck('name', 'id'))
                    ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Название (Только в панели администратора)')
                    ->searchable(),
                Tables\Columns\TextColumn::make('url')
                    ->label('URL')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bot.alias')
                    ->label('Бот приложения'),
                Tables\Columns\TextColumn::make('bot.name')
                    ->label('Привязанный бот'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Создано')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Изменено')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->recordAction('view')
            ->bulkActions([
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
            'index' => Pages\ListMiniApps::route('/'),
            'create' => Pages\CreateMiniApp::route('/create'),
            'edit' => Pages\EditMiniApp::route('/{record}/edit'),
            'view' => Pages\ViewMiniApp::route('/{record}'),
        ];
    }
}
