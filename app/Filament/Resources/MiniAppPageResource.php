<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MiniAppPageResource\Pages;
use App\Filament\Resources\MiniAppPageResource\RelationManagers;
use App\Models\Core\MiniApp;
use App\Models\Core\MiniAppPage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MiniAppPageResource extends Resource
{
    protected static ?string $model = MiniAppPage::class;

    public static ?string $label = "Страница";
    public static ?string $navigationLabel = "Страницы";

    public static ?string $title = "Страницы";

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static ?int $navigationSort = 3;

    public static function getPluralLabel(): ?string {return "Страницы";}

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('mini_app_id')
                    ->label('Название приложения')
                    ->required()
                    ->options(MiniApp::all()->pluck('name', 'id'))
                    ->searchable(),
                Forms\Components\TextInput::make('name')
                    ->label('Название страницы')
                    ->maxLength(255),
                Forms\Components\TextInput::make('url')
                    ->label('Ссылка')
                    ->required()
                    ->maxLength(255)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('miniapp.name')
                    ->label('Название приложения')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Название страницы')
                    ->searchable(),
                Tables\Columns\TextColumn::make('url')
                    ->label('URL')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListMiniAppPages::route('/'),
            'create' => Pages\CreateMiniAppPage::route('/create'),
            //'edit' => Pages\EditMiniAppPage::route('/{record}/edit'),
            'edit' => Pages\AdminMiniAppPage::route('/{record}/admin'),
            'preview' => Pages\PreviewMiniAppPage::route('/{mini_app_page_id}/preview'),
        ];
    }
}
