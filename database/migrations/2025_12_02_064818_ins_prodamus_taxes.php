<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Core\ProdamusTax;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        ProdamusTax::create(['code' => 0, 'name' => 'Без НДС']);
        ProdamusTax::create(['code' => 1, 'name' => 'НДС по ставке 0%;']);
        ProdamusTax::create(['code' => 2, 'name' => 'НДС чека по ставке 10%']);
        ProdamusTax::create(['code' => 4, 'name' => 'НДС чека по расчетной ставке 10/110']);
        ProdamusTax::create(['code' => 6, 'name' => 'НДС чека по ставке 20%']);
        ProdamusTax::create(['code' => 7, 'name' => 'НДС чека по расчетной ставке 20/120']);
        ProdamusTax::create(['code' => 10, 'name' => 'НДС чека по ставке 5%']);
        ProdamusTax::create(['code' => 12, 'name' => 'НДС чека по ставке 7%']);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
