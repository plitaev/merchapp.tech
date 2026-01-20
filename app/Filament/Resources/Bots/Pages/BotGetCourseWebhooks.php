<?php

namespace App\Filament\Resources\Bots\Pages;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

use App\Models\Core\PaySystemCallback;
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
            ->query(PaySystemCallback::where('pay_system_id', 5))
            ->persistSearchInSession()
            ->columns([
                TextColumn::make('created_at')
                    ->label('Создан')
                    ->dateTime('d.m.Y H:i:s'),
                TextColumn::make('callback->product_id')
                    ->label('Продукт')
                    ->formatStateUsing(function (string $state): HtmlString {
                        $data = json_decode($state, true);
                        $output = "Key 1: {$data['key1']}, Key 2: {$data['key2']}";
                        // Or more complicated structures using HtmlString
                        return new HtmlString("<p>{$output}</p>");
                    })
            ])
            ->filters([
                //
            ])
            ->recordActions([

            ]);

    }
}
