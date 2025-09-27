<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Core\YookassaVatCode;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        YookassaVatCode::create(['code' => '1', 'name' => 'Без НДС']);
        YookassaVatCode::create(['code' => '2', 'name' => 'НДС по ставке 0%']);
        YookassaVatCode::create(['code' => '3', 'name' => 'НДС по ставке 10%']);
        YookassaVatCode::create(['code' => '4', 'name' => 'НДС по ставке 20%']);
        YookassaVatCode::create(['code' => '5', 'name' => 'НДС по расчетной ставке 10/110']);
        YookassaVatCode::create(['code' => '6', 'name' => 'НДС по расчетной ставке 20/120']);
        YookassaVatCode::create(['code' => '7', 'name' => 'НДС по ставке 5%']);
        YookassaVatCode::create(['code' => '8', 'name' => 'НДС по ставке 7%']);
        YookassaVatCode::create(['code' => '9', 'name' => 'НДС по расчетной ставке 5/105']);
        YookassaVatCode::create(['code' => '10', 'name' => 'НДС по расчетной ставке 7/107']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
