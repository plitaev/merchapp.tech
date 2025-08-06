<?php

namespace App\Filament\Resources\BotResource\Pages;

use App\Filament\Resources\BotResource;
use App\Models\Core\Bot;
use App\Models\Core\GetcourseWebhook;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;


class BotGetCourseWebhooks extends Page implements HasTable
{
    use InteractsWithTable;


    protected static string $resource = BotResource::class;

    protected static string $view = 'filament.resources.bot-resource.pages.bot-getcourse-webhooks';


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
            ->query(GetcourseWebhook::with('recurrent_name', 'recurrent_status_name')
                ->whereHas('bot', function ($query) {
                    $query->where('bot_id', $this->bot_id);
                }))
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Продукт')
                    ->searchable(),
                Tables\Columns\TextColumn::make('getcourse_id')
                    ->label('GetCourse ID'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Имя')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('recurrent_name.name')
                    ->label('Рекуррент'),
                Tables\Columns\TextColumn::make('recurrent_status_name.name')
                    ->label('Статус рекуррента'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Создан')
                    ->dateTime('d.m.Y H:i:s')
            ])
            ->filters([
                //
            ])
            ->actions([

            ])
            ->recordUrl(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/product-admin")
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);

    }
}
