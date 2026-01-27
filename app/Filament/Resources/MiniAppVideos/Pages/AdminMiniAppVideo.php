<?php

namespace App\Filament\Resources\MiniAppVideos\Pages;

use Illuminate\Contracts\Support\Htmlable;

use App\Filament\Resources\MiniAppVideos\MiniAppVideoResource;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;

use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;

use App\Models\Core\FunnelCondition;
use App\Models\Core\MiniAppVideo;

class AdminMiniAppVideo extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = MiniAppVideoResource::class;

    protected string $view = 'filament.resources.mini-app-video-resource.pages.admin-mini-app-video';


    public static ?string $label = "Условие воронки";
    public static ?string $navigationLabel = "Условие воронки";
    public static ?string $title = "Условие воронки";

    public ?array $data = [];
    public string $name;

    public int $id;

    public function getRecord(): ?Model
    {
        return MiniAppVideo::class;
    }

    public function getTitle(): string|Htmlable
    {
        return $this->name;
    }

    public function getHeading(): string
    {
        if ($this->id > 0) {
            return "Редактировать условие воронки";
        } else {
            return "Добавить условие воронки";
        }
    }

    protected function getHeaderActions(): array
    {
        return [];
    }


    public function mount(int $id): void
    {
        $this->id = $id;
        if (auth()->user()->hasPermissionTo('Update:FunnelCondition')) {

            $data = ($id > 0 ? FunnelCondition::find($id)->toArray() : []);
            $this->name = ($id > 0?$data['name']:'Новая воронка');

            $this->form->fill($data);
        }else{
            redirect('/admin/bots/access');
        }
    }

    protected function getForms(): array
    {
        return ['form'];
    }

    public function form(Schema $schema): Schema
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
                                ->action(function () {

                                    $data = $this->form->getState();

                                    MiniAppVideo::create($data);

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
                    ]),
            ])->statePath('data');
    }
}

