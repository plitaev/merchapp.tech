<?php

namespace App\Filament\Resources\MiniAppVideos\Pages;

use App\Models\Core\MiniAppVideoLinkPage;
use Illuminate\Contracts\Support\Htmlable;

use App\Filament\Resources\MiniAppVideos\MiniAppVideoResource;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;

use Filament\Forms;
use Filament\Forms\Components\FileUpload;
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

    public int $mini_app_page_id;
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


    public function mount(int $mini_app_page_id, int $id): void
    {
        $this->mini_app_page_id = $mini_app_page_id;
        $this->id = $id;

        if (auth()->user()->hasPermissionTo('Update:FunnelCondition')) {

            $data = ($id > 0 ? MiniAppVideo::find($id)->toArray() : []);
            $this->name = ($id > 0?$data['name']:'Новое видео');

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

                                    if ($this->id > 0) {
                                        (int) $video_id = $this->id;
                                        $current_video = MiniAppVideo::select('video')->find($video_id);
                                    } else {
                                        $new_mini_app_video = MiniAppVideo::create($data);
                                        (int) $video_id = $new_mini_app_video->id;
                                    }

                                    $last_pos = MiniAppVideoLinkPage::select('pos')->where('mini_app_page_id', $this->mini_app_page_id)->orderByDesc('pos')->first();
                                    if ($last_pos) {
                                        $pos = $last_pos->pos + 1;
                                    } else {
                                        $pos = 1;
                                    }

                                    MiniAppVideoLinkPage::upsert(
                                        ['mini_app_video_id' => $video_id, 'mini_app_page_id' => $this->mini_app_page_id, 'pos' => $pos],
                                        ['mini_app_page_id', 'mini_app_video_id'],
                                        ['updated_at' => now()]
                                    );

                                    if ($this->id == 0 || $this->id > 0 && $current_video->video != $data['video']) {
                                        $curl=curl_init();
                                        curl_setopt($curl,CURLOPT_URL,"https://api.edgecenter.ru/streaming/vod/videos");
                                        curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
                                        curl_setopt($curl,CURLOPT_POSTFIELDS,'{"video": {"name": "'.$data['name'].'",
                                                    "description": "none",
                                                    "origin_url": "'.env('APP_URL').'/content/'.$data['video'].'"}}');
                                        curl_setopt($curl,CURLOPT_HTTPHEADER,['Content-Type: application/json','Authorization: APIKey 1858$9e823ab46df09abb48e065707137f16155b6b94f0702fb86f9b041a251dda657d3f86596d954cf431e3c73ee6662cf785c25f50e3c454c8264565299abb8c288']);
                                        $result = curl_exec($curl);
                                        curl_close($curl);

                                        $Aresult=json_decode($result,true);
                                        MiniAppVideo::where('id', $video_id)->update(['edgecenter_id' => $Aresult['id']]);
                                    }

                                    Notification::make()
                                        ->title($result)
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

