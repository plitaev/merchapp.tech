<?php

namespace App\Filament\Resources\MiniAppVideos;

use App\Filament\Resources\MiniAppVideos\Pages\AdminMiniAppVideo;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\FileUpload;
use App\Filament\Resources\FunnelConditions\Pages\AdvancedListFunnelCondition;
use App\Filament\Resources\FunnelConditions\Pages\AdminFunnelCondition;
use App\Filament\Resources\FunnelConditionResource\Pages;
use App\Filament\Resources\FunnelConditionResource\RelationManagers;
use App\Models\Core\FunnelCondition;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MiniAppVideoResource extends Resource
{
    protected static ?string $model = FunnelCondition::class;

    public static ?string $label = "Видео";
    public static ?string $navigationLabel = "Видео";
    public static ?string $title = "Видео";

    public static function getPluralLabel(): ?string {return "Видео";}

    protected static bool $shouldRegisterNavigation = false;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
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
            'index' => AdvancedListFunnelCondition::route('/'),
            'create' => AdminMiniAppVideo::route('/{id}/admin'),
            'edit' => AdminMiniAppVideo::route('/{id}/admin'),
            'admin' => AdminMiniAppVideo::route('/{id}/admin'),

        ];
    }
}
