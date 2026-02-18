<?php

namespace App\Filament\Resources\MiniAppBlockTypes\Pages;

use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use App\Filament\Resources\MiniAppBlockTypes\MiniAppBlockTypeResource;
use App\Models\Core\MiniAppBlockType;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class AdvancedListMiniAppBlockType extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = MiniAppBlockTypeResource::class;

    protected string $view = 'filament.resources.mini-app-block-type-resource.pages.advanced-list-mini-app-block-type';

    public static ?string $label = "Тип блока Мини-приложения";
    public static ?string $navigationLabel = "Тип блока Мини-приложения";
    public static ?string $title = "";

    public function mount(): void
    {
        if (!auth()->user()->hasPermissionTo('View:MiniApp')) {
            redirect('/admin/bots/access');
        }

    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(MiniAppBlockType::query())
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
                ViewAction::make()
                    ->visible(!auth()->user()->can('Update:MiniApp')),
                EditAction::make()->url(fn($record) => "/admin/mini-app-block-types/".$record->id."/admin")
                    ->visible(auth()->user()->can('Update:MiniApp')),
                DeleteAction::make()
                    ->visible(auth()->user()->can('Delete:MiniApp')),

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(auth()->user()->can('Delete:MiniApp')),

                ]),
            ])
            ->recordUrl(fn($record) => "/admin/mini-app-block-types/".$record->id."/admin");
    }
}

