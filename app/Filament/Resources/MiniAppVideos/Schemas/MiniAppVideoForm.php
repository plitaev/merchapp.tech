<?php

namespace App\Filament\Resources\MiniAppVideos\Schemas;

use App\Models\Core\MiniAppVideo;
use Filament\Actions\Action;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;

use Filament\Support\Enums\Width;

class MiniAppVideoForm
{
    public static function configure(Schema $schema): Schema
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

                                    $data = SELF::form->getState();

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
}
