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
use App\Models\Core\BotUser;
use App\Models\Core\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Support\Enums\IconPosition;

use Filament\Support\Enums\Width;
use Illuminate\Support\Facades\Auth;
class BotRecurrent extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = BotResource::class;

    protected string $view = 'filament.resources.bot-resource.pages.bot-recurrent';

    public static ?string $label = "";
    public static ?string $navigationLabel = "Подписчики";
    public static ?string $title = "Подписчики";

    public int $bot_id;
    public string $bot_name;

    public string $date_end;

    public function getMaxContentWidth(): Width{return Width::ScreenTwoExtraLarge;}

    public function mount(int $bot_id, string $date_end): void
    {
        $this->bot_id = $bot_id;
        $bot = Bot::select('name')->find($bot_id);

        $this->edit_bots = Permission::where('name', 'edit bots')->count();
        $this->bot_name = $date_end;

        $this->date_end = $date_end;

        if (!Auth::user()->hasPermissionTo('View:BotUser')) {
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
            ->query(BotUser::where('bot_id', $this->bot_id)->where('date_end', $this->date_end)->where('recurrent', 1))
            ->persistSearchInSession()
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->icon('heroicon-m-clipboard')
                    ->iconPosition(IconPosition::Before)
                    ->iconColor('gray')
                    ->extraAttributes(fn (BotUser $record) => [
                        'x-data' => '{}',
                        'x-on:click.prevent' => "
                        if (\$event.target.closest('svg')) {
                         navigator.clipboard.writeText('{$record->first_name}');
                         \$tooltip('Скопировано', { timeout: 1500 });
                        } else {
                         window.location.href = '/admin/bots/".$this->bot_id."/".$record->id."/chat-admin';
                        }"
                    ])
                    ->label('Имя')
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->icon('heroicon-m-clipboard')
                    ->iconPosition(IconPosition::Before)
                    ->iconColor('gray')
                    ->extraAttributes(fn (BotUser $record) => [
                        'x-data' => '{}',
                        'x-on:click.prevent' => "
                        if (\$event.target.closest('svg')) {
                         navigator.clipboard.writeText('{$record->last_name}');
                         \$tooltip('Скопировано', { timeout: 1500 });
                        } else {
                         window.location.href = '/admin/bots/".$this->bot_id."/".$record->id."/chat-admin';
                        }"
                    ])
                    ->label('Фамилия')
                    ->searchable(),
                Tables\Columns\TextColumn::make('username')
                    ->icon('heroicon-m-clipboard')
                    ->iconPosition(IconPosition::Before)
                    ->iconColor('gray')
                    ->extraAttributes(fn (BotUser $record) => [
                        'x-data' => '{}',
                        'x-on:click.prevent' => "
                        if (\$event.target.closest('svg')) {
                         navigator.clipboard.writeText('{$record->username}');
                         \$tooltip('Скопировано', { timeout: 1500 });
                        } else {
                         window.location.href = '/admin/bots/".$this->bot_id."/".$record->id."/chat-admin';
                        }"
                    ])
                    ->label('Ник')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->icon('heroicon-m-clipboard')
                    ->iconPosition(IconPosition::Before)
                    ->iconColor('gray')
                    ->extraAttributes(fn (BotUser $record) => [
                        'x-data' => '{}',
                        'x-on:click.prevent' => "
                        if (\$event.target.closest('svg')) {
                         navigator.clipboard.writeText('{$record->email}');
                         \$tooltip('Скопировано', { timeout: 1500 });
                        } else {
                         window.location.href = '/admin/bots/".$this->bot_id."/".$record->id."/chat-admin';
                        }"
                    ])
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_end')
                    ->date('d.m.Y')
                    ->label('Дата подписки'),
                Tables\Columns\IconColumn::make('recurrent')
                    ->boolean()
                    ->label('Рекуррент')
                    ->alignCenter()
                    ->trueColor('info')
                    ->falseColor('warning'),
                Tables\Columns\TextColumn::make('ban_name.name')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Да' => 'danger',
                        'Нет' => 'success',
                    })
                    ->label('Бан'),
                Tables\Columns\TextColumn::make('ban_time')
                    ->label('Дата/время бана')
            ])
            ->actions([
                ViewAction::make()->url(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/chat-admin")
                    ->visible(!auth()->user()->can('Create:BotUser')),

                EditAction::make()->url(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/chat-admin")
                    ->visible(fn() => auth()->user()->can('Create:BotUser')),


            ])
            ->recordUrl(fn($record) => "/admin/bots/".$this->bot_id."/".$record->id."/chat-admin");
    }

}
