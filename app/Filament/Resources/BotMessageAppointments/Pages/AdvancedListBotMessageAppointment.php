<?php

namespace App\Filament\Resources\BotMessageAppointments\Pages;

use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use App\Filament\Resources\BotMessageAppointments\BotMessageAppointmentResource;
use App\Models\Core\BotMessageAppointment;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class AdvancedListBotMessageAppointment extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = BotMessageAppointmentResource::class;

    protected string $view = 'filament.resources.bot-message-appointment-resource.pages.advanced-list-bot-message-appointment';

    public static ?string $label = "Назначение";
    public static ?string $navigationLabel = "Назначение";
    public static ?string $title = "";

    public function mount(): void
    {
        if (!auth()->user()->hasPermissionTo('Update:BotMessageAppointment')) {
            redirect('/admin/bots/access');
        }

    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(BotMessageAppointment::select('*'))
            ->columns([
                TextColumn::make('name')
                    ->label('Название (Только в панели администратора)')
                    ->searchable(),
                TextColumn::make('alias')
                    ->label('Псевдоним')
                    ->searchable(),
                //
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()
                    ->visible(!auth()->user()->can('Update:BotMessageAppointment')),
                EditAction::make()->url(fn($record) => "/admin/bot-message-appointments/".$record->id."/admin")
                    ->visible(auth()->user()->can('Update:BotMessageAppointment')),

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(auth()->user()->can('Delete:BotMessageAppointment')),
                ]),
            ]);
    }
}

