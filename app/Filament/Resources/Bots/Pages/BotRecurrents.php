<?php

namespace App\Filament\Resources\Bots\Pages;

use App\Models\Core\BotUser;
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

    public array $recurrents = [];

    public function mount(int $bot_id): void
    {
        $this->bot_id = $bot_id;
        $bot = Bot::select('name')->find($bot_id);

        $this->bot_name = $bot->name;

        $recurrents_all = BotUser::select('date_end')->where('date_end', '>=', date('Y-m-d', time()))->where('recurrent', 1)->get();
        $Adates = [];
        foreach ($recurrents_all as $recurrent_all) {
            $Adates[$recurrent_all->date_end][] = 1;
        }

        $recurrent_dates = BotUser::select('date_end')->where('date_end', '>=', date('Y-m-d', time()))->where('recurrent', 1)->orderBy('date_end')->pluck('date_end')->toArray();
        foreach ($recurrent_dates as $recurrent_date) {
            $this->recurrents[] = ['date' => date('d.m.Y', strtotime($recurrent_date)), 'count' => (isset($Adates[$recurrent_date])?count($Adates[$recurrent_date]):0)];
        }



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
            ->records(fn (): array => $this->recurrents)
            ->columns([
                TextColumn::make('date'),
                TextColumn::make('count')
            ]);
    }
}
