<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Core\TbankTax;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        TbankTax::create(['code' => 'none', 'name' => 'Без НДС']);
        TbankTax::create(['code' => 'vat0', 'name' => 'НДС по ставке 0%']);
        TbankTax::create(['code' => 'vat5', 'name' => 'НДС по ставке 5%']);
        TbankTax::create(['code' => 'vat7', 'name' => 'НДС по ставке 7%']);
        TbankTax::create(['code' => 'vat10', 'name' => 'НДС по ставке 10%']);
        TbankTax::create(['code' => 'vat20', 'name' => 'НДС по ставке 20%']);
        TbankTax::create(['code' => 'vat22', 'name' => 'НДС по ставке 22%']);
        TbankTax::create(['code' => 'vat105', 'name' => 'НДС чека по расчетной ставке 5/105']);
        TbankTax::create(['code' => 'vat107', 'name' => 'НДС чека по расчетной ставке 7/107']);
        TbankTax::create(['code' => 'vat110', 'name' => 'НДС чека по расчетной ставке 10/110']);
        TbankTax::create(['code' => 'vat120', 'name' => 'НДС чека по расчетной ставке 20/120']);
        TbankTax::create(['code' => 'vat122', 'name' => 'НДС чека по расчетной ставке 22/122']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
