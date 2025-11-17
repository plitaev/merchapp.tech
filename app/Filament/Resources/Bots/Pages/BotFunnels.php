<?php

namespace App\Filament\Resources\Bots\Pages;

use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Bots\BotResource;
use App\Models\Core\Bot;
use App\Models\Core\Funnel;
use App\Models\Core\User;

use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class BotFunnels extends Page implements HasTable
{
    use InteractsWithTable;


    protected static string $resource = BotResource::class;

    protected string $view = 'filament.resources.bot-resource.pages.bot-funnels';


    public static ?string $label = "Воронки";
    public static ?string $navigationLabel = "Воронки";
    public static ?string $title = "";

    public int $bot_id;
    public string $bot_name;

    public function mount(int $bot_id): void
    {
        $this->bot_id = $bot_id;
        $bot = Bot::select('name')->find($bot_id);

        $this->bot_name = $bot->name;

        if (!Auth::user()->hasPermissionTo('View:Funnel')) {
            redirect('/admin/bots/access');
        }
    }

    public function getHeading(): string
    {
        return $this->bot_name;
    }

    public function getTitle(): string
    {
        return $this->bot_name;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Funnel::where('bot_id', $this->bot_id))
            ->persistSearchInSession()
            ->columns([
                TextColumn::make('name')
                    ->label('Наименование')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Создан')
                    ->dateTime('d.m.Y H:i:s')
            ])

            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()->url(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/funnel-admin")
                    ->visible(!auth()->user()->can('Create:Funnel')),
                EditAction::make()->url(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/funnel-admin")
                    ->visible(auth()->user()->can('Create:Funnel')),
                DeleteAction::make()
                    ->visible(auth()->user()->can('Delete:Funnel')),

            ])
            ->recordUrl(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/funnel-admin")
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(auth()->user()->can('Delete:Funnel')),

                ]),
            ]);
    }
}
