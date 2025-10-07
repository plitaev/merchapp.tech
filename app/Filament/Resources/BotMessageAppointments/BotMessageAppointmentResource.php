<?php

namespace App\Filament\Resources\BotMessageAppointments;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\BotMessageAppointments\Pages\ListBotMessageAppointments;
use App\Filament\Resources\BotMessageAppointments\Pages\CreateBotMessageAppointment;
use App\Filament\Resources\BotMessageAppointments\Pages\EditBotMessageAppointment;
use App\Filament\Resources\BotMessageAppointmentResource\Pages;
use App\Filament\Resources\BotMessageAppointmentResource\RelationManagers;
use App\Models\Core\BotMessageAppointment;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BotMessageAppointmentResource extends Resource
{
    protected static ?string $model = BotMessageAppointment::class;

    public static ?string $label = "Назначение";
    public static ?string $navigationLabel = "Назначение";
    public static ?string $title = "Назначение";

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static bool $shouldRegisterNavigation = false;

    public static function getPluralLabel(): ?string {return "Назначение";}

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Название (Только в панели администратора)')
                    ->required()
                    ->maxLength(255),
                TextInput::make('alias')
                    ->label('Псевдоним')
                    ->required()
                    ->maxLength(255),
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
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
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBotMessageAppointments::route('/'),
            'create' => CreateBotMessageAppointment::route('/create'),
            'edit' => EditBotMessageAppointment::route('/{record}/edit'),
        ];
    }
}
