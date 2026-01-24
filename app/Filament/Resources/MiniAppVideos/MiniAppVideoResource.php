<?php

namespace App\Filament\Resources\MiniAppVideos;

use BackedEnum;

use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

use App\Filament\Resources\MiniAppVideos\Pages\CreateMiniAppVideo;
use App\Filament\Resources\MiniAppVideos\Pages\EditMiniAppVideo;
use App\Filament\Resources\MiniAppVideos\Pages\ListMiniAppVideos;
use App\Filament\Resources\MiniAppVideos\Schemas\MiniAppVideoForm;
use App\Filament\Resources\MiniAppVideos\Tables\MiniAppVideosTable;

use App\Models\Core\MiniAppVideo;

class MiniAppVideoResource extends Resource
{
    protected static ?string $model = MiniAppVideo::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Видео';

    public static function form(Schema $schema): Schema
    {
        return MiniAppVideoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MiniAppVideosTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMiniAppVideos::route('/'),
            'create' => CreateMiniAppVideo::route('/create'),
            'edit' => EditMiniAppVideo::route('/{record}/edit'),
        ];
    }
}
