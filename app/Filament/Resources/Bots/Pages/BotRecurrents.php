<?php
namespace App\Filament\Resources\Bots\Pages;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

use Filament\Resources\Pages\Page;
use App\Filament\Resources\Bots\BotResource;

use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;


use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\TextColumn;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

use App\Models\Core\Bot;
use App\Models\Core\BotUser;

class BotRecurrents extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;


    protected static string $resource = BotResource::class;

    protected string $view = 'filament.resources.bot-resource.pages.bot-recurrents';


    public static ?string $label = "Автосписания";
    public static ?string $navigationLabel = "Автосписания";
    public static ?string $title = "";

    public int $bot_id;
    public string $bot_name;

    public array $recurrents = [];
    public string $recurrents_by_months = "";

    public function mount(int $bot_id): void
    {
        $this->bot_id = $bot_id;
        $bot = Bot::select('name')->find($bot_id);

        $this->bot_name = $bot->name;

        $Adates = [];
        $Amys = [];
        $Amys_users = [];

        $recurrents_all = BotUser::select('date_end')->where('date_end', '>=', date('Y-m-d', time()))->where('recurrent', 1)->get();
        foreach ($recurrents_all as $recurrent_all) {
            $Adates[$recurrent_all->date_end][] = 1;
            $Amys_users[date("m.Y", strtotime($recurrent_all->date_end))][] = 1;
        }

        $recurrent_dates = BotUser::select('date_end')->where('date_end', '>=', date('Y-m-d', time()))->where('recurrent', 1)->groupBy('date_end')->orderBy('date_end')->pluck('date_end')->toArray();
        foreach ($recurrent_dates as $recurrent_date) {
            $this->recurrents[] = ['date' => $recurrent_date, 'count' => (isset($Adates[$recurrent_date])?count($Adates[$recurrent_date]):0)];
            $Amys[] = date("m.Y", strtotime($recurrent_date));
        }

        $Amys = array_unique($Amys);

        $recurrents_by_months = "";
        foreach ($Amys as $my) {
            $recurrents_by_months .= "<p style='display: block; margin-bottom: 10px; font-weight:bold'>".$my." - ".(isset($Amys[$my])?count($Amys[$my]):0)."</p>";
        }

        $this->recurrents_by_months = $recurrents_by_months;


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
