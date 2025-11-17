<?php

namespace App\Filament\Resources\Bots\Pages;

use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\Bots\BotResource;
use App\Models\Core\Bot;
use App\Models\Core\Product;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class BotGetCourseSettings extends Page implements HasTable
{
    use InteractsWithTable;


    protected static string $resource = BotResource::class;

    protected string $view = 'filament.resources.bot-resource.pages.bot-getcourse-settings';


    public static ?string $label = "Тарифы";
    public static ?string $navigationLabel = "Тарифы";
    public static ?string $title = "";

    public int $bot_id;
    public string $bot_name;

    public function mount(int $bot_id): void
    {
        $this->bot_id = $bot_id;
        $bot = Bot::select('name')->find($bot_id);

        $this->bot_name = $bot->name;

        if (!Auth::user()->hasPermissionTo('View:Bot')) {
            redirect('/access');
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
            ->query(Product::with('product_type')->where(['bot_id' => $this->bot_id]))
            ->persistSearchInSession()
            ->columns([
                ViewColumn::make('webhook')->view('filament.resources.product-resource.columns.getcourse_webhook')
                    ->label('Вебхук'),
                TextColumn::make('name')
                    ->label('Название')
                    ->searchable(),
                TextColumn::make('product_type.name')
                    ->label('Тип'),
                TextColumn::make('price')
                    ->label('Стоимость'),
                TextColumn::make('days')
                    ->label('Дни')
            ])
            ->filters([
                //
            ])
            ->recordActions([

            ]);

    }
}
