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

    public string $recurrents_by_months = "";

    public function mount(int $bot_id): void
    {
        $this->bot_id = $bot_id;
        $bot = Bot::select('name')->find($bot_id);

        $this->bot_name = $bot->name;

        //== Рекурренты

        $recurrents_all = BotUser::select('date_end')->where('date_end', '>=', date('Y-m-d', time()))->where('recurrent', 1)->orderByDesc('date_end')->get();

        $Ayears_months = [];
        $Adates = [];

        foreach ($recurrents_all as $recurrent_all) {
            $date_ym = date('m.Y', strtotime($recurrent_all->date_end));
            $Ayears_months[] = $date_ym;
            $Adates[$date_ym][] = 1;
        }

        array_unique($Ayears_months);

        foreach ($Ayears_months as $value) {
            $this->recurrents_by_months. = $value;
        }

        //==


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

    protected function getForms(): array
    {
        return ['form'];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Статистика по месяцам')
                    ->description(new HtmlString($this->recurrents_by_months))
                    ->columns([
                        'sm' => 4,
                        'md' => 4,
                        'lg' => 4,
                        'xl' => 4,
                        '2xl' => 4,
                    ])
                    ->schema([]),
            ])->statePath('data');
    }

    public function table(Table $table): Table
    {
        return $table
            ->records(fn (): array => $this->recurrents)
            ->columns([
                TextColumn::make('date')->label('Дата списания автоплатежа')
                    ->date('d.m.Y'),
                TextColumn::make('count')->label('Количество списаний'),
            ])
            ->recordUrl(fn($record) => "/admin/bots/".$this->bot_id."/".$record['date']."/recurrent");
    }
}
