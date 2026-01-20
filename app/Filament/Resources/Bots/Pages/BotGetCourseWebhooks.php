<?php

namespace App\Filament\Resources\Bots\Pages;

use Filament\Tables\Columns\TextColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Bots\BotResource;
use App\Models\Core\Bot;
use App\Models\Core\GetcourseWebhook;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class BotGetCourseWebhooks extends Page implements HasTable
{
    use InteractsWithTable;


    protected static string $resource = BotResource::class;

    protected string $view = 'filament.resources.bot-resource.pages.bot-getcourse-webhooks';


    public static ?string $label = "Вебхуки GetCourse";
    public static ?string $navigationLabel = "Вебхуки GetCourse";
    public static ?string $title = "";

    public int $bot_id;
    public string $bot_name;

    public function mount(int $bot_id): void
    {
        $this->bot_id = $bot_id;
        $bot = Bot::select('name')->find($bot_id);

        $this->bot_name = $bot->name;

        if (!Auth::user()->hasPermissionTo('View:Bot')) {
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
            ->defaultSort('created_at', 'desc')
            ->query(GetcourseWebhook::with('recurrent_name', 'recurrent_status_name')
                ->whereHas('bot', function ($query) {
                    $query->where('bot_id', $this->bot_id);
                }))
            ->persistSearchInSession()
            ->columns([
                TextColumn::make('created_at')
                    ->label('Создан')
                    ->dateTime('d.m.Y H:i:s'),
                TextColumn::make('product.name')
                    ->label('Продукт')
                    ->searchable(),
                TextColumn::make('getcourse_id')
                    ->label('GetCourse ID'),
                TextColumn::make('name')
                    ->label('Имя')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('recurrent_name.name')
                    ->label('Рекуррент'),
                TextColumn::make('recurrent_status_name.name')
                    ->label('Статус рекуррента')
            ])
            ->filters([
                //
            ])
            ->recordActions([

            ]);

    }
}
