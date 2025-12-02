<?php

namespace App\Filament\Resources\Bots\Pages;

use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use App\Filament\Resources\Bots\BotResource;
use App\Models\Core\Bot;
use App\Models\Core\Product;
use App\Models\Core\User;

use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class BotRecurrents extends Page implements HasTable
{
    use InteractsWithTable;


    protected static string $resource = BotResource::class;

    protected string $view = 'filament.resources.bot-resource.pages.bot-recurrents';


    public static ?string $label = "Автосписания";
    public static ?string $navigationLabel = "Автосписания";
    public static ?string $title = "";

    public int $bot_id;
    public string $bot_name;

    public function mount(int $bot_id): void
    {
        $this->bot_id = $bot_id;
        $bot = Bot::select('name')->find($bot_id);

        $this->bot_name = $bot->name;

        if (!Auth::user()->hasPermissionTo('View:Pay')) {
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
            ->query(Product::with('product_type')->where('bot_id', $this->bot_id))
            ->persistSearchInSession()
            ->columns([
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
                ViewAction::make()->url(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/product-admin")
                    ->visible(!auth()->user()->can('Create:Pay')),
                EditAction::make()->url(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/product-admin")
                    ->visible(auth()->user()->can('Create:Pay')),

                DeleteAction::make()
                    ->visible(auth()->user()->can('Delete:Pay')),

            ])
            ->recordUrl(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/product-admin")
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(auth()->user()->can('Delete:Pay')),

                ]),
            ]);
    }
}
