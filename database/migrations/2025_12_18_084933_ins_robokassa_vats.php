<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Core\RobokassaVAT;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        RobokassaVAT::create(['code' => 'none', 'name' => 'Без НДС']);
        RobokassaVAT::create(['code' => 'vat0', 'name' => 'НДС по ставке 0%']);
        RobokassaVAT::create(['code' => 'vat10', 'name' => 'НДС чека по ставке 10%']);
        RobokassaVAT::create(['code' => 'vat110', 'name' => 'НДС чека по расчетной ставке 10/110']);
        RobokassaVAT::create(['code' => 'vat20', 'name' => 'НДС чека по ставке 20%']);
        RobokassaVAT::create(['code' => 'vat120', 'name' => 'НДС чека по расчетной ставке 20/120']);
        RobokassaVAT::create(['code' => 'vat5', 'name' => 'НДС по ставке 5%']);
        RobokassaVAT::create(['code' => 'vat7', 'name' => 'НДС по ставке 7%']);
        RobokassaVAT::create(['code' => 'vat105', 'name' => 'НДС чека по расчетной ставке 5/105']);
        RobokassaVAT::create(['code' => 'vat107', 'name' => 'НДС чека по расчетной ставке 7/107']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
