<?php
namespace App\Filament\Resources\MiniAppVideos\Schemas;
use Filament\Schemas\Schema;

class MiniAppVideoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Изображение на баннере')
                    ->description('Как пользователь видит этот баннер')
                    ->schema([
                        TextInput::make('name')
                            ->label('Название видео')
                            ->disabled(auth()->user()->hasPermissionTo('Update:BotMessage')?false:true)
                            ->maxLength(255),
                        FileUpload::make('image')
                            ->disk('local')
                            ->directory('miniapp_banners')
                            ->disabled(auth()->user()->hasPermissionTo('Update:MiniAppPage')?false:true)
                            ->visibility('public')
                    ]),
                Actions::make([
                    Action::make('Сохранить')
                        ->action(function () {

                            Notification::make()
                                ->title('Данные успешно сохранены!')
                                ->success()
                                ->send();
                        })
                        ->visible(fn() => auth()->user()->can('Create:BotMessage')),

                    Action::make('Cancel')
                        ->action(function () {

                        })
                        ->label('Вернуться назад')
                ])
            ])->statePath('data');
    }
}
