<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BotMessageAppointmentResource\Pages;
use App\Filament\Resources\BotMessageAppointmentResource\RelationManagers;
use App\Models\Core\BotMessageAppointment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BotMessageAppointmentResource extends Resource
{
    protected static ?string $model = BotMessageAppointment::class;

    public static ?string $label = "Назначение";
    public static ?string $navigationLabel = "Назначение";
    public static ?string $title = "Назначение";

    protected static bool $shouldRegisterNavigation = false;

    public static function getPluralLabel(): ?string {return "Назначение";}

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Название (Только в панели администратора)')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('alias')
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
                Tables\Columns\TextColumn::make('name')
                    ->label('Название (Только в панели администратора)')
                    ->searchable(),
                Tables\Columns\TextColumn::make('alias')
                    ->label('Псевдоним')
                    ->searchable(),
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListBotMessageAppointments::route('/'),
            'create' => Pages\CreateBotMessageAppointment::route('/create'),
            'edit' => Pages\EditBotMessageAppointment::route('/{record}/edit'),
        ];
    }
}
