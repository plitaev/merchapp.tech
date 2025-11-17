<?php

namespace App\Filament\Resources\Listeners\Pages;

use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use App\Filament\Resources\Listeners\ListenerResource;
use Livewire\Features\SupportRedirects\Redirector;
use App\Models\Core\Listener;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class AdvancedListListener extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = ListenerResource::class;

    protected string $view = 'filament.resources.listener-resource.pages.advanced-list-listener';

    public static ?string $label = "Ожидания";
    public static ?string $navigationLabel = "Ожидания";
    public static ?string $title = "";


    public function mount(): void
    {
        if (!auth()->user()->hasPermissionTo('Update:Listener')) {
            redirect('/access');
        }

    }
    public static function table(Table $table): Table
    {
            return $table
                ->query(Listener::select('*'))
                ->persistSearchInSession()
                ->columns([
                    TextColumn::make('name')
                        ->label('Наименование')
                        ->searchable(),
                    TextColumn::make('alias')
                        ->label('Alias')
                        ->searchable()
                ])
                ->filters([
                    //
                ])
                ->recordActions([
                    ViewAction::make()
                        ->visible(!auth()->user()->can('Update:Listener')),
                    EditAction::make()->url(fn($record) => "/admin/listeners/" . $record->id . "/admin")
                        ->visible(auth()->user()->can('Update:Listener')),
                    DeleteAction::make()
                        ->visible(auth()->user()->can('Delete:Listener')),

                ])
                ->toolbarActions([
                    BulkActionGroup::make([
                        DeleteBulkAction::make()
                            ->visible(auth()->user()->can('Delete:Listener')),

                    ]),
                ])
                ->recordUrl(fn($record) => "/admin/listeners/" . $record->id . "/admin");

    }
}

