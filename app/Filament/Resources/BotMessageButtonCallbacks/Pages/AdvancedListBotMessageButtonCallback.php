<?php

namespace App\Filament\Resources\BotMessageButtonCallbacks\Pages;

use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\BotMessageButtonCallbacks\BotMessageButtonCallbackResource;
use App\Models\Core\BotMessageButtonCallback;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;


class AdvancedListBotMessageButtonCallback extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = BotMessageButtonCallbackResource::class;

    protected string $view = 'filament.resources.bot-message-button-callback-resource.pages.advanced-list-bot-message-button-callback';


    public static ?string $label = "Обработчики кнопок";
    public static ?string $navigationLabel = "Обработчики кнопок";
    public static ?string $title = "";

    public function mount(): void
    {
        if (!auth()->user()->hasPermissionTo('View:BotMessageButtonCallback')) {
            redirect('/admin/bots/access');
        }

    }
    public static function table(Table $table): Table
    {
        return $table
            ->query(BotMessageButtonCallback::select('*'))
            ->persistSearchInSession()
            ->columns([
                TextColumn::make('name')
                    ->label('Наименование')
                    ->searchable(),
                TextColumn::make('system_name')
                    ->label('System_name')
                    ->searchable()
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()->url(fn($record) => "/admin/bot-message-button-callbacks/".$record->id."/admin")
                    ->visible(auth()->user()->can('Update:BotMessageButtonCallback')),
                DeleteAction::make()
                    ->visible(auth()->user()->can('Delete:BotMessageButtonCallback')),

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(auth()->user()->can('Delete:BotMessageButtonCallback')),

                ]),
            ])
            ->recordUrl(fn($record) => "/admin/bot-message-button-callbacks/".$record->id."/admin");
    }
}


