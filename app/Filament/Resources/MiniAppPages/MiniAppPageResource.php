<?php

namespace App\Filament\Resources\MiniAppPages;

use App\Filament\Resources\MiniAppPages\Pages\AdvancedListMiniAppPage;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\MiniAppPages\Pages\ListMiniAppPages;
use App\Filament\Resources\MiniAppPages\Pages\CreateMiniAppPage;
use App\Filament\Resources\MiniAppPages\Pages\AdminMiniAppPage;
use App\Filament\Resources\MiniAppPages\Pages\PreviewMiniAppPage;
use App\Filament\Resources\MiniAppPageResource\Pages;
use App\Filament\Resources\MiniAppPageResource\RelationManagers;
use App\Models\Core\MiniApp;
use App\Models\Core\MiniAppPage;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MiniAppPageResource extends Resource
{
    protected static ?string $model = MiniAppPage::class;

    public static ?string $label = "Страницу";
    public static ?string $navigationLabel = "Страницы";

    public static ?string $title = "Страницы";

    protected static bool $shouldRegisterNavigation = false;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';
    public static ?int $navigationSort = 3;

    public static function getPluralLabel(): ?string {return "Страницы";}

    public function mount(): void
    {
        if (!auth()->user()->hasPermissionTo('Update:MiniAppPage')) {
            redirect('/admin/bots/access');
        }

    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('mini_app_id')
                    ->label('Название приложения')
                    ->required()
                    ->options(MiniApp::all()->pluck('name', 'id'))
                    ->searchable(),
                TextInput::make('name')
                    ->label('Название страницы')
                    ->maxLength(255),
                TextInput::make('url')
                    ->label('Ссылка')
                    ->required()
                    ->maxLength(255)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->persistSearchInSession()
            ->columns([
                TextColumn::make('miniapp.name')
                    ->label('Название приложения')
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Название страницы')
                    ->searchable(),
                TextColumn::make('url')
                    ->label('URL')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()->url(fn($record) => "/admin/mini-app-pages/".$record->id."/admin")
                    ->visible(!auth()->user()->can('Update:MiniAppPage')),
                EditAction::make()->url(fn($record) => "/admin/mini-app-pages/".$record->id."/admin")
                    ->visible(auth()->user()->can('Update:MiniAppPage')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => AdvancedListMiniAppPage::route('/'),
            'create' => AdminMiniAppPage::route('/create'),
            //'edit' => Pages\EditMiniAppPage::route('/{record}/edit'),
            'edit' => AdminMiniAppPage::route('/{record}/admin'),
            'preview' => PreviewMiniAppPage::route('/{mini_app_page_id}/preview'),
        ];
    }
}
