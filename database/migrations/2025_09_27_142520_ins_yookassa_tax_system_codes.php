<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Core\YookassaTaxSystemCode;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        YookassaTaxSystemCode::create(['code' => '1', 'name' => 'Общая система налогообложения']);
        YookassaTaxSystemCode::create(['code' => '2', 'name' => 'Упрощенная (УСН, доходы)']);
        YookassaTaxSystemCode::create(['code' => '3', 'name' => 'Упрощенная (УСН, доходы минус расходы)']);
        YookassaTaxSystemCode::create(['code' => '4', 'name' => 'Единый налог на вмененный доход (ЕНВД)']);
        YookassaTaxSystemCode::create(['code' => '5', 'name' => 'Единый сельскохозяйственный налог (ЕСН)']);
        YookassaTaxSystemCode::create(['code' => '6', 'name' => 'Патентная система налогообложения']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
