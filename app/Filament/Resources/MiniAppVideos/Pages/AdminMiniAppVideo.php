<?php

namespace App\Filament\Resources\MiniAppVideos\Pages;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

use App\Filament\Resources\MiniAppVideos\MiniAppVideoResource;

use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Hidden;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Section;

use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;


use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;

use App\Actions\Core\MiniAppVideo\MiniAppVideoTimePointAdminDeleteRecord;

use App\Models\Core\MiniAppVideo;
use App\Models\Core\MiniAppVideoLinkPage;
use App\Models\Core\MiniAppVideoTimePoint;

class AdminMiniAppVideo extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static string $resource = MiniAppVideoResource::class;

    protected string $view = 'filament.resources.mini-app-video-resource.pages.admin-mini-app-video';


    public static ?string $label = "Видео";
    public static ?string $navigationLabel = "Видео";
    public static ?string $title = "Видео";

    public ?array $data = [];
    public string $name;

    public int $mini_app_page_block_id = 0;
    public int $video_page_count = 0;

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
            return "Редактировать Видео";
        } else {
            return "Добавить Видео";
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

        //$this->mini_app_page_block_id = $mini_app_page_block_id;
        $this->video_page_count = MiniAppVideoLinkPage::where('mini_app_video_id', $id)->count();

        if (auth()->user()) {

            $data = ($id > 0 ? MiniAppVideo::find($id)->toArray() : ['name' => 'Новое видео']);
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
                            ->disabled(auth()->user()->hasPermissionTo('Update:MiniAppPage')?false:true)
                            ->maxLength(255),
                        DatePicker::make('date_open')
                            ->label('Дата открытия видео')
                            ->format('Y-m-d')
                            ->disabled(auth()->user()->hasPermissionTo('Update:MiniAppPage')?false:true),
                        Textarea::make('description')
                            ->label('Описание видео')
                            ->extraInputAttributes(['style' => 'height: 500px'])
                            ->disabled(auth()->user()->hasPermissionTo('Update:MiniAppPage')?false:true),
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
                        FileUpload::make('audio')
                            ->label('Файл аудио')
                            ->disk('local')
                            ->acceptedFileTypes(['audio/mpeg'])
                            ->directory('miniapp_audio')
                            ->disabled(auth()->user()->hasPermissionTo('Update:MiniAppPage')?false:true)
                            ->visibility('public'),
                        Section::make('Страницы')
                            ->description(new HtmlString("<a href='/admin/mini-app-videos/".$this->mini_app_page_id."/".$this->id."/admin_mini_app_video_link_pages' style='display: block; margin-bottom: 10px; font-weight: bold'>Прикрепленные страницы: {$this->video_page_count} 🔍</a>"))
                            ->schema([]),
                        Actions::make([
                            Action::make('Сохранить')
                                ->action(function () {
                                    $data = $this->form->getState();

                                    if ($this->id > 0) {
                                        $video_id = $this->id;
                                        $current_video = MiniAppVideo::select('video')->find($video_id);
                                        MiniAppVideo::where('id', $video_id)->update($data);
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

                                    if (isset($data['video']) && ($this->id == 0 || ($this->id > 0 && $current_video->video != $data['video']))) {
                                        $curl=curl_init();
                                        curl_setopt($curl,CURLOPT_URL,"https://api.edgecenter.ru/streaming/vod/videos");
                                        curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
                                        curl_setopt($curl,CURLOPT_POSTFIELDS,'{"video": {"name": "'.$data['name'].'",
                                                    "description": "none",
                                                    "origin_url": "'.env('APP_URL').'/content/'.$data['video'].'"}}');
                                        curl_setopt($curl,CURLOPT_HTTPHEADER,['Content-Type: application/json','Authorization: APIKey '.env('EDGECENTER_API_KEY')]);
                                        $result = curl_exec($curl);
                                        curl_close($curl);

                                        $Aresult=json_decode($result,true);

                                        MiniAppVideo::where('id', $video_id)->update(
                                            [
                                                'edgecenter_id' => $Aresult['id'],
                                                'edgecenter_name'=>$Aresult['name'],
                                                'duration'=>$Aresult['duration'],
                                                'edgecenter_slug'=>$Aresult['slug'],
                                                'edgecenter_status'=>$Aresult['status'],
                                                'edgecenter_screenshot_url'=>$Aresult['screenshot'],
                                                'edgecenter_hls_url'=>$Aresult['hls_url'],
                                                'edgecenter_views'=>$Aresult['views'],
                                            ]
                                        );

                                    }

                                    Notification::make()
                                        ->title("Данные успешно сохранены")
                                        ->success()
                                        ->send();

                                    return redirect('/admin/mini-app-videos/' . $this->mini_app_page_id . '/' . $video_id . '/admin');

                                })
                                ->visible(fn() => auth()->user()->can('Create:BotMessage')),

                            Action::make('Cancel')
                                ->action(function () {
                                    return redirect('/admin/mini-app-pages/'.$this->mini_app_page_id.'/admin');
                                })
                                ->color('gray')
                                ->label('Вернуться назад')
                        ])
                    ]),
            ])->statePath('data');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(MiniAppVideoTimePoint::with('miniapp_video')->where('mini_app_video_id', $this->id))
            ->persistSearchInSession()
            ->columns([
                TextColumn::make('name')
                    ->label('Название точки')
                    ->disabled(auth()->user()->hasPermissionTo('Update:MiniAppPage')?false:true)
                    ->searchable(),
                TextColumn::make('point')
                    ->label('Время')
                    ->disabled(auth()->user()->hasPermissionTo('Update:MiniAppPage')?false:true)
                    ->searchable(),

            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()->url(fn($record) => "/admin/mini-app-videos/".$this->mini_app_page_id."/".$this->id."/".$record->id."/admin-mini-app-video-time-point")
                    ->visible(!auth()->user()->can('Create:MiniAppPage')),
                EditAction::make()->url(fn($record) => "/admin/mini-app-videos/".$this->mini_app_page_id."/".$this->id."/".$record->id."/admin-mini-app-video-time-point")
                    ->visible(auth()->user()->can('Update:MiniAppPage')),
                DeleteAction::make()
                    ->before(function (DeleteAction $action, MiniAppVideoTimePoint $record) {
                        $miniAppVideoTimePointAdminDeleteRecord = new MiniAppVideoTimePointAdminDeleteRecord();
                        $miniAppVideoTimePointAdminDeleteRecord->handle($record, $action);
                    })
                    ->visible(auth()->user()->can('Delete:MiniAppPage')),

            ])
            ->recordUrl(fn($record) => "/admin/mini-app-videos/".$this->mini_app_page_id."/".$this->id."/".$record->id."/admin-mini-app-video-time-point")
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

}
