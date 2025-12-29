<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Core\TbankTaxation;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        TbankTaxation::create(['code' => 'osn', 'name' => 'Общая СН']);
        TbankTaxation::create(['code' => 'usn_income', 'name' => 'Упрощенная СН (доходы)']);
        TbankTaxation::create(['code' => 'usn_income_outcome', 'name' => 'Упрощенная СН (доходы минус расходы);']);
        TbankTaxation::create(['code' => 'esn', 'name' => 'Единый сельскохозяйственный налог']);
        TbankTaxation::create(['code' => 'patent', 'name' => 'Патентная СН']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
