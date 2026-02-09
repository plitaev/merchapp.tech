<?php

namespace App\Filament\Resources\MiniAppVideos\Pages;

use App\Models\Core\MiniAppVideoLinkPage;
use Illuminate\Contracts\Support\Htmlable;

use App\Filament\Resources\MiniAppVideos\MiniAppVideoResource;

use Filament\Tables\Columns\TextColumn;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;


use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;

use App\Models\Core\FunnelCondition;
use App\Models\Core\MiniAppVideo;
use App\Models\Core\MiniAppVideoTimePoint;

class AdminMiniAppVideoTimePoint extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = MiniAppVideoResource::class;

    protected string $view = 'filament.resources.mini-app-video-resource.pages.admin-mini-app-video-time-point';


    public static ?string $label = "Время видео";
    public static ?string $navigationLabel = "Время видео";
    public static ?string $title = "Время видео";

    public ?array $data = [];
    public string $name;

    public int $id;

    public int $mini_app_page_id;
    public int $mini_app_video_id;

    protected static bool $shouldRegisterNavigation = false;

    public function getTitle(): string|Htmlable
    {
        return $this->name;
    }

    public function getHeading(): string
    {
        if ($this->mini_app_video_id > 0) {
            return "Редактировать время видео";
        } else {
            return "Добавить время видео";
        }
    }

    protected function getHeaderActions(): array
    {
        return [];
    }


    public function mount(int $mini_app_page_id, int $mini_app_video_id, int $id): void
    {
        $this->mini_app_page_id = $mini_app_page_id;

        $this->mini_app_video_id = $mini_app_video_id;

        $this->id = $id;

        if (auth()->user()->hasPermissionTo('Update:FunnelCondition')) {

            $data = ($id > 0 ? MiniAppVideoTimePoint::find($id)->toArray() : ['name' => 'Новое название точки']);
            $this->name = ($id > 0?$data['name']:'Новое время видео');

            $this->form->fill($data);
        } else {
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
                Section::make('Видео')
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                        '2xl' => 1,
                    ])
                    ->schema([
                        Hidden::make('mini_app_video_id'),
                        TextInput::make('name')
                            ->label('Название')
                            ->disabled(auth()->user()->hasPermissionTo('Update:BotMessage')?false:true)
                            ->maxLength(255),
                        TimePicker::make('point')
                            ->label('Время')
                            ->disabled(auth()->user()->hasPermissionTo('Update:BotMessage')?false:true),
                        Actions::make([
                            Action::make('Сохранить')
                                ->action(function () {
                                    $data = $this->form->getState();
                                    $data['mini_app_video_id'] = $this->mini_app_video_id;
                                    if ($this->id > 0) {
                                        MiniAppVideoTimePoint::where('id', $this->id)->update($data);
                                    } else {
                                        $new_mini_app_video = MiniAppVideoTimePoint::create($data);
                                    }

                                    Notification::make()
                                        ->title("Данные успешно сохранены")
                                        ->success()
                                        ->send();

                                    return redirect('/admin/mini-app-videos/'.$this->mini_app_page_id.'/'.$this->mini_app_video_id.'/admin');
                                }),

                            Action::make('Cancel')
                                ->action(function () {
                                    return redirect('/admin/mini-app-videos/'.$this->mini_app_page_id.'/'.$this->mini_app_video_id.'/admin');
                                })
                                ->color('gray')
                                ->label('Вернуться назад')
                        ])
                    ]),
            ])->statePath('data');
    }

}


