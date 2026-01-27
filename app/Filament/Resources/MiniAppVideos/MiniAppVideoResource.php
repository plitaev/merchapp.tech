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

    protected static bool $shouldRegisterNavigation = false;

    public function getMaxContentWidth(): Width{return Width::ScreenTwoExtraLarge;}

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Изображение на баннере')
                    ->description('Как пользователь видит этот баннер')
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                        '2xl' => 1,
                    ])
                    ->schema([
                        TextInput::make('name')
                            ->label('Название видео')
                            ->disabled(auth()->user()->hasPermissionTo('Update:BotMessage')?false:true)
                            ->maxLength(255),
                        FileUpload::make('image')
                            ->label('Заставка видео')
                            ->disk('local')
                            ->directory('miniapp_video_banners')
                            ->disabled(auth()->user()->hasPermissionTo('Update:MiniAppPage')?false:true)
                            ->visibility('public'),
                        FileUpload::make('video')
                            ->label('Файл видео')
                            ->disk('local')
                            ->directory('miniapp_video')
                            ->disabled(auth()->user()->hasPermissionTo('Update:MiniAppPage')?false:true)
                            ->visibility('public'),
                        Actions::make([
                            Action::make('Сохранить')
                                ->action(function (Get $get) {

                                    if (is_callable($get)) {
                                        Notification::make()
                                            ->title($get('name'))
                                            ->success()
                                            ->send();
                                    }
                                })
                                ->visible(fn() => auth()->user()->can('Create:BotMessage')),

                            Action::make('Cancel')
                                ->action(function () {

                                })
                                ->label('Вернуться назад')
                        ])
                    ]),
            ])->statePath('data');
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
            'create' => CreateMiniAppVideo::route('/{mini_app_page_id}/create'),
            'edit' => EditMiniAppVideo::route('/{record}/edit'),
        ];
    }
}
